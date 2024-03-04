<?php

namespace App\Listeners;

use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Role;

class RemoveDefaultRole
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->user instanceof User && $event->user->hasRole('mourner')) {
            $event->user->removeRole('mourner');
        }
    }
}
