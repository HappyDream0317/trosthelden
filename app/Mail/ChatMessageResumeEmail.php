<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatMessageResumeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $newMessageCount;
    public $chatUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $newMessageCount)
    {
        $this->user = $user;
        $this->newMessageCount = $newMessageCount;
        $this->chatUrl = config('app.url') . '/chat/';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = __('Guck mal, wer da schreibt! Du hast eine Nachricht von deinem Trauerfreund');
        $this->subject($title);

        return $this->markdown('emails.notifications.chat_message_resume');
    }
}
