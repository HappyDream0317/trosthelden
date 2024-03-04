<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class B2BDiscountPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'b2_b_discount';


    /*
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function delete(User $user, $model): bool
    {
        if (
            $user->hasPermissionTo('delete ' . static::$key) &&
            $model->b2bPartner->b2bUser->user->id === $user->id
        ) {
            return true;
        }

        return false;
    }


    /*
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function update(User $user, $model): bool
    {
        if (
            $user->hasPermissionTo('update ' . static::$key) &&
            $model->b2bPartner->b2bUser->user->id === $user->id
        ) {
            return true;
        }

        return false;
    }

}