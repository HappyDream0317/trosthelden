<?php

namespace App\Mail;

use App\User;
use Illuminate\Mail\Mailable;

class DeleteUserEmail extends Mailable
{
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = __('User löschen!');
        $this->subject($title);
        $novaUrl = config('app.url').'/nova/resources/users/'.$this->user->id;
        return $this->markdown('emails.delete_user.delete_user')
            ->with([
                'title' => $title,
                'user' => $this->user,
                'novaUrl' => $novaUrl
            ]);
    }
}
