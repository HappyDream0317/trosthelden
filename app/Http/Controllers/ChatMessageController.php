<?php

namespace App\Http\Controllers;

use App\ChatConnection;
use App\ChatMessage;
use App\Events\ChatMessageNew;
use App\Events\NewChatContact;
use App\SendinBlue\SendinBlueApi;
use App\SendinBlue\SendinBlueHandler;
use App\SendinBlue\SendinBlueTracker;
use App\User;
use App\UserFriend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class ChatMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Can the current user chat with the given recipient?
     * @param int $recipient_id
     * @return bool
     */
    private function canSendToPrivateChat(int $recipient_id): bool
    {
        //TODO(tom.scholz@yesdevs.com): Check here if both users are part of the current chat.
        return !!Auth::user()->isFriendsWith($recipient_id);
    }

    private function canViewChat($chat_id)
    {
        return ChatConnection::find($chat_id)->isPartOfChat(Auth::id());
    }

    protected static function validator(array $data)
    {
        $rules = [
        'message' => 'required|string'
    ];

        return Validator::make($data, $rules);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function partners(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);


        $chatPartnerIds = ChatConnection::select(['participants', 'updated_at'])
            ->whereJsonContains('participants', $user_id)
            ->orderByDesc('updated_at')
            ->get()
            ->flatMap(function ($result) {
                return $result->participants;
            });

        $friends = UserFriend::where('user_id', $user_id)
            ->withTrashed()
            ->get()
            ->map(function ($userFriend) {
                return $userFriend->foreign_user_id;
            });

        $chatPartnerIds->concat($friends);
        $chatPartnerIds = $chatPartnerIds->unique()->values();

        $unsortedPartners = User::whereIn('id', $chatPartnerIds)
            ->withTrashed()
            ->where('id', '<>', $user_id)
            ->whereNotIn('id', $user->getBlockUsersId())
            ->get();

        $sortedPartners = $chatPartnerIds->transform(function ($partner) use ($unsortedPartners) {
            return $unsortedPartners->first(function ($item) use ($partner) {
                return $item->id === $partner;
            });
        })->filter(function ($value) {
            return !is_null($value);
        })->values()->toArray();

        return response()->json($sortedPartners);
    }

    /**
     * @param Request $request
     * @param  $recipient_id  - integer
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request, $recipient_id)
    {
        if (!is_numeric($recipient_id)) {
            response()->json([
              'error' => 'recipient must be a number (int).',
            ]);
        }

        $recipient_id = (int)$recipient_id;

        $chatroom_id = self::_getOrCreateChatConnection($recipient_id);

        if ($this->canViewChat($chatroom_id)) {
            $history_count = $request->get("history", -1);
            $history = ChatMessage::where('chat_id', $chatroom_id)
              ->orderBy('send_at', 'desc')
              ->take($history_count)
              ->with(['author'])
              ->get();

            return response()->json([
              'chat_id' => $chatroom_id,
              'history' => $history
          ]);
        }

        return response()->json([], 403);
    }

    public function getNewMessageCount()
    {
        $user = Auth::user();
        $newMsgCount = $user->getNewChatMessagesCount();

        return response()->json(['total' => $newMsgCount]);
    }

    public function getNewMessageCountByChat()
    {
        $user = Auth::user();
        $newCount = $user->getNewChatMessageCountByChat()->all();
        unset($newCount[$user->id]);

        $this->eventAtLeast1MessageReceived($user);
        $this->eventMessagesSentAtLeast2People($user);
        
        return response()->json($newCount);
    }

    public function markMsgRead(Request $request, $msg_id)
    {
        $chat_msg = ChatMessage::find($msg_id);

        if (empty($chat_msg)) {
            return response()->json(['error' => 'does not exist'], 404);
        }

        if (!$this->canViewChat($chat_msg->chat_id)) {
            return response()->json(['error' => 'not allowed'], 403);
        }

        $update = ChatMessage::where('id', $msg_id)->whereNull('read_at')->update(['read_at' => Carbon::now()]);

        if (!$update) {
            return response()->json(['error' => 'could not save message'], 500);
        }

        return response()->noContent();
    }

    public function markMessagesAsRead(Request $request, $chat_id, $partner_id)
    {
        $toUpdate = ChatMessage::where([
                'chat_id' => $chat_id,
                'user_id' => $partner_id
            ])
        ->whereNull('read_at');

        $response = $toUpdate->pluck('send_at', 'id');
        $toUpdate->update(['read_at' => Carbon::now()]);
        $response = !empty($response->all()) ? $response->all() : null;

        return response()->json($response, 200);
    }

    /**
     * Send a message to a specific chat, where the current user is a member.
     * @param Request $request
     * @param int $chat_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendMessage(Request $request, int $chat_id)
    {
        $recipient_ids = array_values(array_filter(ChatConnection::find($chat_id)->participants, function ($participant) {
            return $participant != Auth::id();
        }));

        if (count($recipient_ids) !== 1) {
            return response()->json(['error' => 'only chats with 2 people supported'], 500);
        }

        if ($this->canSendToPrivateChat($recipient_ids[0])) {            
            return response()->json($this->_sendMessageToChatInternal($request, $chat_id)->toArray());
        }

        return response()->json([], 403);
    }


    /**
     * Send a message to a chat WARNING: doesn't validate if the sender is part of the chat
     * @param Request $request
     * @param int $recipient_id
     * @param int|null $chat_id
     * @param int|null $user_id
     * @return ChatMessage
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function _sendMessageToChatInternal(Request $request, int $chat_id, $user_id = null)
    {
        //validate the request
        self::validator($request->all())->validate();

        $user_id = $user_id == null ? Auth::id() : $user_id;

        // create the message
        $message = new ChatMessage();
        $message->chat_id = $chat_id;
        $message->user_id = $user_id;
        $message->message = $request->get('message');
        $message->meta = $request->get('meta');

        // save to the db.
        if ($message->save()) {
            $updated_message = $message->fresh(['author']);

            /** @var $connection ChatConnection*/
            $connection = ChatConnection::find($message->chat_id, ['id', 'participants', 'updated_at']);
            $connection->touch();

            $recipient = array_diff($connection->participants, [$message->user_id]);
            broadcast(new ChatMessageNew($updated_message, $recipient))->toOthers();

            return ChatMessage::find($message->id);
        } else {
            throw new RuntimeException("Could not save message", 500);
        }
    }

    /**
     * Get or create a ChatConnection instance with the $recipient_id and the current user as members.
     * @param int $recipient_id
     * @param int $user_id
     * @return int
     */
    public static function _getOrCreateChatConnection(int $recipient_id, int $user_id = null)
    {
        $user_id = $user_id == null ? Auth::id() : $user_id;
        $user = User::find($user_id);

        $chat_connection = ChatConnection::whereJsonContains('participants', [$user_id, $recipient_id])->first();

        if (!empty($chat_connection) && $chat_connection instanceof ChatConnection) {
            return $chat_connection->getKey();
        }

        $connection = new ChatConnection();
        $connection->participants = [$user_id, $recipient_id];
        $connection->created_at = Carbon::now();
        $connection->updated_at = Carbon::now();
        $connection->save();

        broadcast(new NewChatContact($user, $recipient_id, 1)); // 1: FRIEND_REQUEST_RECEIVED

        return $connection->id;
    }

    protected function eventMessagesSentAtLeast2People(User $user) {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitMessagesSentAtLeast2People();
    }

    protected function eventAtLeast1MessageReceived(User $user) {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitAtLeast1MessageReceived();
    }
}
