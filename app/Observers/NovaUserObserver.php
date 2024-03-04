<?php

namespace App\Observers;

use App\Events\RegisteredSupporter;
use App\User;
use App\SendinBlue\SendinBlueHandler;
use App\Events\RegisteredNova;

class NovaUserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function created(User $user)
    {
        event(new RegisteredNova($user));
    }

    /**
     * Handle the User "updating" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function updating(User $user)
    {
        $this->updateSendinBluePremiumStatus($user);
        $this->updateMatchingStatus($user);
    }

    public function updateSendinBluePremiumStatus(User $user)
    {
        if ($user->isDirty('is_premium')) {
            $newPremium = $user->is_premium;
            $oldPremium = $user->getOriginal('is_premium');

            $sendinBlue = new SendinBlueHandler($user);
            $method = (!$oldPremium && $newPremium) ? "premiumStatusStart" : "premiumStatusEnd";
            $sendinBlue->$method();
        }
    }

    public function updateMatchingStatus(User $user)
    {
        $isPremiumDirty = ($user->isDirty('is_premium') || $user->isDirty('force_premium'));
        if(!$user->matching_status && $isPremiumDirty) {
            $user->matching_status = true;
        }
    }


    /**
     * Handle the user "updated" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
