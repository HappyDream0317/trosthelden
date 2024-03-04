<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TerminationInternalMail extends Mailable
{
    use Queueable, SerializesModels;

    private $regular;
    private $user;
    private $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, array $params)
    {
        $this->user = $user;
        $this->regular = boolval($params["regular"]);
        $this->reason =  $params["reason"];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return ($this->regular) ? $this->sendProper() : $this->sendExtraordinary();
    }

    public function sendProper()
    {
        $this->subject(__("Info: ordentliche Kündigung: {$this->user->id}"));
        return $this->markdown('emails.payments.termination.internal.proper', [
            'user' => $this->user,
            'reason' => $this->reason
        ]);

    }

    public function sendExtraordinary()
    {
        $this->subject(__("Außerordentliche Kündigung: {$this->user->id}"));
        return $this->markdown('emails.payments.termination.internal.extraordinary', [
            'user' => $this->user,
            'reason' => $this->reason
        ]);

    }
}
