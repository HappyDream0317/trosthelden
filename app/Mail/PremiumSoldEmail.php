<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PremiumSoldEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return PremiumSoldEmail
     */
    public function build()
    {
        $title = __('Herzlich Willkommen bei TrostHelden Premium');
        $this->subject($title);

        try {
            return $this->markdown('emails.payments.premium');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            \Log::debug($msg);
        }
    }
}
