<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class TerminationErrorInternalMail extends Mailable
{
    use Queueable, SerializesModels;

    private $regular;
    private $user;
    private $message;
    private $reason;
    private $endDate;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, array $params, string $message)
    {
        $this->user = $user;
        $this->regular = $params["regular"];
        $this->reason =  $params["reason"];
        $this->message =  $message;
        $this->endDate =  Carbon::parse(substr($params["contractEndDate"],0, 10));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->regular? "ordentliche Kündigung" : "Außerordentliche Kündigung";
        $this->subject(__("Error: {$title}: {$this->user->id}"));
        return $this->markdown('emails.payments.termination.internal.error', [
            'user' => $this->user,
            'endDate' =>  $this->endDate,
            'reason' => $this->reason,
            'message' => $this->message,
        ]);
    }
}
