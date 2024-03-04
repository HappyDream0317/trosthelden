<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestConfirmation;
use App\Events\FriendRequestChanged;
use App\Events\FriendRequestNew;
use App\Events\PartnerStatusChanged;
use App\Helpers\FriendRequestHelper;
use App\Helpers\FriendshipHelper;
use App\Helpers\CollectionHelper;
use App\Mail\DeleteUserEmail;
use App\MatchingUserPartnerRanking;
use App\SendinBlue\SendinBlueHandler;
use App\SendinBlue\SendinBlueApi;
use App\SendinBlue\SendinBlueTracker;
use App\User;
use App\UserBlockListItem;
use App\UserFriend;
use App\UserFriendRequest;
use App\UserWatchListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['getPartnerCodePerId']);
    }

    public function getAllBlocklist(Request $request)
    {
        $blockList = UserBlockListItem::where('user_id', Auth::id())->get();

        return response()->json($blockList);
    }

    public function getBlocklist(Request $request)
    {
        $perPage = $request->get('perPage') ?? null;

        $blockList = UserBlockListItem::where('user_id', Auth::id())->paginate($perPage);

        return response()->json($blockList);
    }

    public function addToBlocklist($foreign_user_id)
    {
        $user_id = Auth::id();

        $return = DB::table('user_blocklist')->updateOrInsert([
            'user_id' => $user_id,
            'blocked_user_id' => $foreign_user_id
        ]);


        if ($return) {
            $blockUser =  UserBlockListItem::where('user_id', $user_id)
                            ->where('blocked_user_id', $foreign_user_id)
                            ->first();

            return response()->json([
                'status' => true,
                'user' => $blockUser,
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
    }

    public function removeFromBlocklist($foreign_user_id)
    {
        $user_id = Auth::id();

        $return = DB::table('user_blocklist')->where([['user_id', $user_id], ['blocked_user_id', $foreign_user_id]])->delete();

        if ($return) {
            return response()->json([
                'status' => true,
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
    }

    public function isOnBlocklist($foreign_user_id)
    {
        $user_id = Auth::id();
        $blocklist = DB::table('user_blocklist')->where(
            [
                ['user_id', $user_id],
                ['blocked_user_id', $foreign_user_id],
            ]
        )->get()->first();

        if ($blocklist) {
            return response()->json([
                'status' => true,
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
    }

    /**
     * @param $foreign_user_id
     * @return \Illuminate\Http\JsonResponse
     * @deprecated
     */
    public function isUserOnFriendlist($foreign_user_id)
    {
        $user_id = Auth::id();
        $friendlist = DB::table('user_friendlist')->where(
            [
            ['user_id', $user_id],
            ['foreign_user_id', $foreign_user_id],
        ]
        )->get()->first();

        if ($friendlist) {
            return response()->json([
          'status' => true,
      ]);
        }

        $friendrequestlist = UserFriendRequest::where([
        ['user_id', $user_id],
        ['foreign_user_id', $foreign_user_id],
    ])->get()->first();

        if ($friendrequestlist) {
            return response()->json([
          'status' => true,
      ]);
        }

        return response()->json([
        'status' => false,
    ]);
    }

    public function isOnFriendlistOrRequestlist($foreign_user_id)
    {
        $user_id = Auth::id();

        $return = UserFriendRequest::where([['user_id', $user_id], ['foreign_user_id', $foreign_user_id]])->get()->first();
        if ($return) {
            return response()->json([
          'status' => true,
      ]);
        }

        $return = UserFriend::where([['user_id', $user_id], ['foreign_user_id', $foreign_user_id]])->get()->first();
        if ($return) {
            return response()->json([
          'status' => true,
      ]);
        }

        return response()->json([
        'status' => false,
    ]);
    }

    public function getFriendRequests()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        $requests = UserFriendRequest::with(['user'])
            ->where('added_user_id', $user_id)
            ->whereNotIn('user_id', $user->getBlockUsersId())
            ->paginate();

        UserFriendRequest::whereIn('id', $requests->modelKeys())
            ->whereNull('first_displayed_at')
            ->update(['first_displayed_at' => Carbon::now()]);
        UserFriendRequest::whereIn('id', $requests->modelKeys())
            ->update(['last_displayed_at' => Carbon::now()]);

        return response()->json($requests);
    }

    public function getMatches(Request $request)
    {

        $user_id = Auth::id();
        $user = User::find($user_id);

        $perPage = $request->get('perPage') ?? 15;

        $matchings = MatchingUserPartnerRanking::where('user_id', $user_id)
            ->where('partner_id', '!=', $user_id)
            ->whereNotIn('partner_id', $user->getBlockUsersId())
            ->whereHas('partner', function ($q) {
                $q->where('matching_step', '-1');
                $q->where('matching_status', true);
                $q->whereNotNull('nickname');
                $q->whereNotNull('last_seen_at');
                $q->where('last_seen_at', '>', Carbon::now()->subDays(config('matching.last-seen')));
            })
            ->orderBy('rank', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $premiumMatchings = $matchings->filter(function ($matching) {
            return $matching->partner->is_premium || $matching->partner->force_premium;
        });

        $nonPremiumMatchings = $matchings->filter(function ($matching) {
            return !$matching->partner->is_premium && !$matching->partner->force_premium;
        });

        $results = $nonPremiumMatchings->zip($premiumMatchings)->collapse()->reject(null);

        $matchings = CollectionHelper::paginate($results, $perPage);

        $dataKeys = collect($matchings->items())->pluck('id')->all();

        MatchingUserPartnerRanking::whereIn('id', $dataKeys)
            ->whereNull('first_display')
            ->update(['first_display' => Carbon::now()]);

        MatchingUserPartnerRanking::whereIn('id', $dataKeys)
            ->update(['last_display' => Carbon::now()]);

        MatchingUserPartnerRanking::whereIn('id', $dataKeys)
            ->increment('display_number');

        return response()->json($matchings);
    }

    public function getWatchlist(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        $perPage = $request->get('perPage') ?? null;
        
        $watchlist = UserWatchListItem::where('user_id', $user_id)
            ->whereNotIn('watched_user_id', $user->getBlockUsersId())
            ->paginate($perPage);

        return response()->json($watchlist);
    }

    public function isOnWatchlist($foreign_user_id)
    {
        $user_id = Auth::id();

        $return = DB::table('user_watchlist')->where(['user_id' => $user_id], ['watched_user_id', $foreign_user_id])->get()->first();

        if ($return) {
            return response()->json([
          'status' => true,
      ]);
        } else {
            return response()->json([
          'status' => false,
      ]);
        }
    }

    public function addToWatchlist($foreign_user_id)
    {
        $user_id = Auth::id();

        $return = DB::table('user_watchlist')->updateOrInsert(
            ['user_id' => $user_id, 'watched_user_id' => $foreign_user_id]
        );

        return response()->json([
            'status' => $return,
        ]);
    }

    public function removeFromWatchlist($foreign_user_id)
    {
        $user_id = Auth::id();

        $return = DB::table('user_watchlist')->where([['user_id', $user_id], ['watched_user_id', $foreign_user_id]])->delete();

        return response()->json([
        'status' => $return,
    ]);
    }

    public function getPartnerCodePerId(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        abort_if(!$user, 404, 'Customer not found');

        return response()->json([
            'b2b_partner_id' => $user->b2b_partner_id ?? null
        ]);
    }

    public function getFriends()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        $friends = UserFriend::where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                    ->orWhere('foreign_user_id', $user_id);
            })
            ->where(function ($query) use ($user) {
                $query->whereNotIn('user_id', $user->getBlockUsersId())
                    ->whereNotIn('foreign_user_id', $user->getBlockUsersId());
            })
            ->paginate();

        return response()->json($friends);
    }

    public function getFriendStatus($foreign_user_id)
    {
        $user = Auth::user();
        return response()->json([
            'status' => $user->getFriendStatus($foreign_user_id),
        ]);
    }

    public function getCountOfNewMatchings()
    {
        $matchings = MatchingUserPartnerRanking::selectRaw('count(*) as total')
        ->where('user_id', Auth::id())
        ->where('partner_id', '!=', Auth::id())
        ->whereNull('first_display')
        ->first();

        return response()->json($matchings ?: ['total' => 0]);
    }

    public function getCountOfNewFriendRequests()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        $result = UserFriendRequest::without(['chatMessage'])
            ->where('added_user_id', $user_id)
            ->whereNull('first_displayed_at')
            ->whereNotIn('user_id', $user->getBlockUsersId())
            ->get()
            ->count();

        // return blocked users
        return response()->json([
            'total' => $result,
            'id' => $user_id,
            'blockUsersId' => $user->getBlockUsersId()
        ]);
    }

    public function getUsersFromWatchlist()
    {
        $user_id = Auth::id();

        $return_array = [];

        $users = DB::table('user_watchlist')->where('user_id', $user_id)->select('watched_user_id')->get();

        foreach ($users as $user) {
            $return_array[] = $user->watched_user_id;
        }

        return response()->json(['users' => $return_array]);
    }

    public function getProfileImage()
    {
        return Auth::user()->getMedia('profile_images')->first()->getUrl('thumb');
    }

    /**
     * @deprecated ?
    */
    public function uploadImage(Request $request)
    {
        $user = Auth::User();
        if (isset($request['profile_image'])) {
            try {
                $user->addMediaFromRequest('profile_image')
            ->usingFileName(uniqid())
            ->toMediaCollection('profile_images');

                return response()->json([
            'success' => true,
            'avatar' => Auth::user()->avatar,
        ]);
            } catch (\Exception $e) {
                return response()->json([
            'success' => false,
            'avatar' => null,
            'error' => get_class($e),
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
        ]);
            }
        }
    }

    /**
     * @param Request $request
     * @param $recipient_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendFriendRequest(Request $request, $recipient_id)
    {
        $current_user = Auth::id();
        $chat_connection_id = ChatMessageController::_getOrCreateChatConnection($recipient_id);

        if (!$request->filled("message")) {
            $request->replace(['message' => 'Hallo, ich möchte gern dein Trauerfreund sein. Dein Profil gefällt mir sehr.']);
        }

        $chatMessage = ChatMessageController::_sendMessageToChatInternal($request, $chat_connection_id)->fresh();

        $friend_request = UserFriendRequest::updateOrCreate(
            [
                'user_id' => $current_user,
                'added_user_id' => $recipient_id
            ],
            [
                'user_id' => $current_user,
                'added_user_id' => $recipient_id,
                'chat_message_id' => $chatMessage->getKey() ?? null
            ]
        );

        $newPartner = User::find($recipient_id);

        if ($friend_request !== null) {
            event(new FriendRequestChanged($current_user,$recipient_id));
            event(new FriendRequestNew($friend_request));
            event(new PartnerStatusChanged(Auth::id(), $recipient_id, 1));
        }

        return response()->json([
            'success' => $friend_request !== null,
            'partner' => $newPartner,
            'message' => $chatMessage
        ]);
    }

    public function retractFriendRequest(Request $request, $foreign_user_id)
    {
        $chat_connection_id = ChatMessageController::_getOrCreateChatConnection($foreign_user_id);
        if ($request->filled("message")) {
            $chatMessage = ChatMessageController::_sendMessageToChatInternal($request, $chat_connection_id)->fresh();
        }

        $response = FriendRequestHelper::deleteAllFriendRequests(Auth::id(), $foreign_user_id);

        event(new PartnerStatusChanged(Auth::id(), $foreign_user_id, 3));
        event(new FriendRequestChanged(Auth::id(), $foreign_user_id));

        return response()->json([
            'success' => (bool)$response,
            'message' => $chatMessage
        ]);
    }

    public function denyFriendRequest(Request $request, $foreign_user_id)
    {
        $chat_connection_id = ChatMessageController::_getOrCreateChatConnection($foreign_user_id);

        // TODO: Allow empty messages?
        if (!$request->filled("message")) {
            $request->replace(['message' => 'Hallo, ich habe deine Trauerfreund-Anfrage abgelehnt.']);
        }

        $chatMessage = ChatMessageController::_sendMessageToChatInternal($request, $chat_connection_id)->fresh();

        $response = FriendRequestHelper::deleteAllFriendRequests(Auth::id(), $foreign_user_id);

        event(new PartnerStatusChanged(Auth::id(), $foreign_user_id, 3));

        return response()->json([
            'success' => (bool) $response,
            'message' => $chatMessage
        ]);
    }

    public function acceptFriendRequest(Request $request, $foreign_user_id)
    {
        $chat_connection_id = ChatMessageController::_getOrCreateChatConnection($foreign_user_id);

        // TODO: Allow empty messages?
        if (!$request->filled("message")) {
            $request->replace(['message' => 'Hallo, ich freue mich auf unsere Trauerfreundschaft.']);
        }

        $chatMessage = ChatMessageController::_sendMessageToChatInternal($request, $chat_connection_id)->fresh();

        $friendRequest = FriendRequestHelper::queryFriendRequest(Auth::id(), $foreign_user_id);

        if (!$friendRequest) {
            return response()->json([
                'status' => false,
            ], 410); // gone
        }

        $friendRequest->delete();

        $friendship = new UserFriend();
        $friendship->user_id = $foreign_user_id;
        $friendship->foreign_user_id = Auth::id();

        if ($friendship->save()) {
            // Behind this event the user will receive an email as well.
            // Do not use PartnerStatusChanged
            event(new FriendRequestConfirmation($friendship));
            // This event has to be sent as well in case the friendship was cancelled and reestablished
            event(new PartnerStatusChanged(Auth::id(), $foreign_user_id, 2));

            return response()->json([
              'success' => true,
              'message' => $chatMessage
            ]);
        }

        return response()->json([
             'success' => false,
            ], 500);
    }

    public function removeFriend(Request $request, $foreign_user_id)
    {
        $chat_connection_id = ChatMessageController::_getOrCreateChatConnection($foreign_user_id);
        $chatMessage = null;

        if ($request->filled("message")) {
            $chatMessage = ChatMessageController::_sendMessageToChatInternal($request, $chat_connection_id)->fresh();
        }

        $friendship = FriendshipHelper::queryFriendship(Auth::id(), $foreign_user_id);

        if ($friendship->delete()) {
            event(new PartnerStatusChanged(Auth::id(), $foreign_user_id, 3));
        }
        return response()->json([
            'success' => (bool)$friendship,
            'message' => $chatMessage
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $user->requested_to_delete_at = Carbon::now();

        if ($user->save()) {
            Mail::to('abo@trosthelden.de')
                ->send(new DeleteUserEmail(
                    $user
                ));

            return response()->json([
                'requested_to_delete_at' => Carbon::now(),
                'success' => true,
                'url' => config('app.url')
            ]);
        }
        return response()->json([
            'success' => false,
        ]);
    }

    public function revokeDeleteAccount(Request $request)
    {
        $user = Auth::user();
        $user->requested_to_delete_at = null;

        if ($user->save()) {
            return response()->json([
                'requested_to_delete_at' => null,
                'success' => true,
            ]);
        }
        return response()->json([
            'success' => false,
        ]);
    }

    public function resetMatching()
    {
        $user = Auth::user();
        $user->resetMatching();
        $user->matching_step = 1;
        $user->assignRole('mourner');
        return response()->json([
            'success' => $user->save(),
        ]);
    }

    public function saveNotifications(Request $request)
    {
        $notifications = $request->json('notifications') ?? null;

        $user = Auth::user();
        $user->notification_settings = $notifications;

        if ($user->save()) {

            $sendinBlue = new SendinBlueHandler($user);
            $sendinBlue->notificationStatus($user->notification_settings);

            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function getNotifications(Request $request)
    {
        return response()->json([
            'notifications' => Auth::user()->notification_settings,
            'success' => true,
        ]);
    }

    public function saveHasSeen(Request $request)
    {
        $user = Auth::user();
        $element = $request->get('element') ?? null;

        if ($element && !$user->hasSeenElement($element)) {
            $hasSeen = $user->has_seen;
            $hasSeen[] = $element;
            $user->has_seen = $hasSeen;
            $success = $user->save();
        }

        return response()->json([
            'success' => $success ?? false,
        ]);
    }

    public function isPremium(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'isPremium' => $user->force_premium ? true : (bool) $user->is_premium,
        ]);
    }

    
    public function setPremium(Request $request)
    {
        $user = Auth::user();
        
        abort_if(!$user, 404, 'User not found');
        
        $user->is_premium = true;
        $user->cancellation_at = null;
        $success = $user->save();

        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitUserPremium($request->all());

        return response()->json([
            'success' => $success ?? false,
        ]);
    }


    public  function setPremiumPaywallSeen(Request $request){
        $user = Auth::user();
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitPremiumPaywallSeen();
    }

    public function setMatchingStatus(Request $request) {
        $user = Auth::user();
        $user->matching_status = $request->get('status');
        $success = $user->save();

        return response()->json([
            'success' => $success ?? false,
        ]);
    }

}
