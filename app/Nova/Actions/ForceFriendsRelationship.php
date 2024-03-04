<?php

namespace App\Nova\Actions;

use App\Http\Controllers\ChatMessageController;
use App\User;
use App\UserFriend;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ForceFriendsRelationship extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return __('Force friend relationship');
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        /**
         * @var $newFriend int
         * @var $user User
         * @var $friendship UserFriend
        */

        $newFriendId = $fields['user'];
        $message = $fields['message'];

        foreach ($models as $user) {
            $alreadyExist = UserFriend::query()
                ->where(function ($q) use ($user, $newFriendId) {
                    return $q->where('user_id', $newFriendId)
                        ->where('foreign_user_id', $user->id);
                })->orWhere(function ($q) use ($user, $newFriendId) {
                    return $q->where('foreign_user_id', $newFriendId)
                        ->where('user_id', $user->id);
                })->exists();

            if ($alreadyExist) {
                continue;
            }

            $chat_connection_id = ChatMessageController::_getOrCreateChatConnection($newFriendId, $user->id);

            $request = new \Illuminate\Http\Request();
            $request->replace(['message' => $message]);
            ChatMessageController::_sendMessageToChatInternal($request, $chat_connection_id, $user->id);

            $friendship = resolve(UserFriend::class);
            $friendship->user_id = $newFriendId;
            $friendship->foreign_user_id = $user->id;
            $friendship->save();
        }

        return 1;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make(__('User'), 'user')
                ->options(function () {
                    return User::all()->pluck('nickname', 'id');
                })
                ->rules('required'),
            Textarea::make(__('Initial message'), 'message')
                ->rules('required')
                ->default(function () {
                    return 'Force relationship through nova';
                })
        ];
    }
}
