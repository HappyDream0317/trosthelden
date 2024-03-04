<?php

namespace App\Mail\B2B;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderSuccessfulCouponCompanyEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    private $user;
    private $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $customer)
    {
        $this->user = $user;
        $this->customer = $customer;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: __('Ihre TrostHelden Bestellbestätigung'),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.b2b.oder_successful_coupon_company',
            with: [
                'user' => $this->user,
                'customer' => $this->customer,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
