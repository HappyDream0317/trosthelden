<?php

namespace App\Listeners\B2B;

use App\Events\RegisteredCompany;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendEmailVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\RegisteredCompany  $event
     * @return void
     */
    public function handle(RegisteredCompany $event)
    {
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->sendCompanyEmailVerificationNotification();
        }
    }
}