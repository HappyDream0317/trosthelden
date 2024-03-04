<?php

namespace App\Listeners;

use App\Helpers\UserNotifications;
use App\Mail\FriendRequestEmail;
use App\User;
use Illuminate\Support\Facades\Mail;

class SendFriendRequestNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = User::find($event->target_user_id);
        if ($user->canSendNotification(UserNotifications::FRIEND_REQUEST)) {
            $friend = User::find($event->requesting_user_id);

            Mail::to($user->email)
                ->send(new FriendRequestEmail($user, $friend));
        }
    }
}
