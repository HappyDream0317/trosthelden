<?php

namespace App\Listeners;

use App\Helpers\UserNotifications;
use App\Mail\FriendRequestConfirmationEmail;
use App\User;
use Illuminate\Support\Facades\Mail;

class SendConfirmFriendRequestNotification
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
        $friend = User::find($event->foreign_user_id);
        $user = User::find($event->user_id);

        if ($user->canSendNotification(UserNotifications::FRIEND_REQUEST_CONFIRMATION)) {
            Mail::to($user->email)
                ->send(new FriendRequestConfirmationEmail($user, $friend));
        }
    }
}
