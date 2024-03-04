<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FriendRequestConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $friend;
    public $chatUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $friend)
    {
        $this->user = $user;
        $this->friend = $friend;
        $this->chatUrl = config('app.url') . '/chat/';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = __('Deine Trauerfreund-Anfrage wurde angenommen!');
        $this->subject($title);

        return $this->markdown('emails.notifications.friend_request_confirmation');
    }
}
