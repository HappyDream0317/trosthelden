<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class TerminationUserMail extends Mailable
{
    use Queueable, SerializesModels;

    private $regular;
    private $user;
    private $endDate;
    private $product;
    private $reason;
    private $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, array $params, object $customer)
    {
        $this->user = $user;
        $this->customer = $customer;
        $this->regular = boolval($params["regular"]);
        $this->product = $this->getProductMonth($params);
        $this->reason =  $params["reason"];
        $this->endDate = Carbon::parse($params["contractEndDate"])->timeZone(config('app.timezone'));
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
        $this->subject(__("Kündigung deiner TrostHelden-Mitgliedschaft"));
        return $this->markdown('emails.payments.termination.user.proper', [
            'user' => $this->user,
            'customer' => $this->customer,
            'product' => $this->product,
            'endDate' =>  $this->endDate,
            'reason' => $this->reason
        ]);

    }

    public function sendExtraordinary()
    {
        $this->subject(__("Außerordentliche Kündigung deiner TrostHelden-Mitgliedschaft"));
        return $this->markdown('emails.payments.termination.user.extraordinary', [
            'user' => $this->user,
            'customer' => $this->customer,
            'product' => $this->product,
            'endDate' =>  $this->endDate,
            'reason' => $this->reason
        ]);
    }

    public function getProductMonth($params): string
    {
        return $this->getProductMonthByName($params["planVariantName"]) ?? $this->getProductMonthById($params["planVariantId"]);
    }

    public function getProductMonthByName($planVariantName): ?int
    {
        preg_match_all('/\d+/', $planVariantName, $matches);
        if(!empty($matches)) {
            $numbers = $matches[0];
            return intval($numbers[0]);
        }
        return null;
    }

    public function getProductMonthById($planVariantId): ?int
    {
        $months = null;
        $plans = config('billwerk.plans.standard');

        switch ($planVariantId){
            case $plans['1_months']:
                $months = 1;
                break;
            case $plans['3_months']:
                $months = 3;
                break;
            case $plans['6_months']:
                $months = 6;
                break;
        }
        return $months;
    }
}
