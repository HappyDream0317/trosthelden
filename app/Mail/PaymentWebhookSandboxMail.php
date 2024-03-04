<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentWebhookSandboxMail extends Mailable
{
    use Queueable, SerializesModels;

    private $payload;
    private $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload, $title)
    {
        $this->payload = $payload;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject($this->title);
        return $this->markdown('emails.payments.sandbox', ['payload' => $this->payload]);
    }
}
