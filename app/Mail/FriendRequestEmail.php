<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FriendRequestEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $friend;
    public $friendRequestUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $friend)
    {
        $this->user = $user;
        $this->friend = $friend;
        $this->friendRequestUrl = config('app.url') . '/partner/';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = __('Schau mal in dein Profil: Du hast eine Trauerfreund-Anfrage!');
        $this->subject($title);

        return $this->markdown('emails.notifications.friend_request');
    }
}
