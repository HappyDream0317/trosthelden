<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{

    /**
     * Handle the User "deleting" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        $fileName = 'uploads/'.$user->nickname.'.png';
        Storage::disk('public')->delete($fileName);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $user->nullAttributes();
        $user->deleteNotRequireRelations();
    }
}
