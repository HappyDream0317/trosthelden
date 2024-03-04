<?php

namespace App\Listeners;

use App\Services\EventService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SignUpEventNotificationListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     * @throws \Exception
     */
    public function handle(object $event): void
    {
        EventService::notify(
            $event->user,
            EventService::SIGN_UP_EVENT,
        );
    }
}
