<?php

namespace App\Listeners;

use App\User;
use Spatie\Permission\Models\Role;

class SetDefaultRole
{

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->user instanceof User && !$event->user->hasAnyRole(Role::all())) {
            $event->user->assignRole('mourner');
        }
    }
}
