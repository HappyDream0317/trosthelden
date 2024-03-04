<?php

namespace App\Mail;

use App\BillwerkCoupon;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CouponSoldEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $customer;
    private $plan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($planVariant, $customer)
    {
        $this->plan = $planVariant;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = __('Dein TrostHelden Gutscheincode');
        $this->subject($title);

        try {
            $coupon = $this->fetchNextPdf();
            if (!$coupon) {
                throw new \Exception('no-pdf');
            }

            $this->markPdfAsSent($coupon);

            return $this->markdown('emails.payments.coupon')
                ->attachFromStorageDisk('public', $coupon->file);
        } catch (\Exception $e) {
            $msg = $e->getMessage();

            if ($msg === 'no-pdf') {
                Mail::to('abo@trosthelden.de')
                    ->send(new PaymentWebhookSandboxMail($this->customer, 'Es gab kein PDF Coupon mehr. Bitte Coupon manuell senden.'));

                \Log::debug($msg . ' for customer: ' . $this->customer->EmailAddress);
            } else {
                \Log::debug($msg);
            }
        }
    }

    public function fetchNextPdf()
    {
        $period = '';
        switch ($this->plan->InternalName) {
            case 'Gutschein 1 Monat':
                $period = '1m';
                break;
            case 'Gutschein 3 Monate':
                $period = '3m';
                break;
            case 'Gutschein 6 Monate':
                $period = '6m';
                break;
        }

        return BillwerkCoupon::where([
            'sent_at' => null,
            'period' => $period

        ])->orderBy('created_at', 'asc')->first();
    }

    public function markPdfAsSent(BillwerkCoupon $coupon)
    {
        BillwerkCoupon::where('id', $coupon->id)->update(['sent_at' => Carbon::now()]);
    }
}
