<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
class Policy
{
    use HandlesAuthorization;

    public function before($user, $ability): bool|null
    {
        if ($user->is_super_admin) {
            return true;
        }
        return null;
    }

    /*
     * Determine whether the user can create models.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo('create ' . static::$key) ) {
            return true;
        }

        return false;
    }

    /*
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function delete(User $user, $model): bool
    {
        if ($user->hasPermissionTo('delete ' . static::$key) ) {
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
        if ($user->hasPermissionTo('update ' . static::$key)) {
            return true;
        }

        return false;
    }


    /*
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function view(User $user, $model): bool
    {
        if ($user->hasPermissionTo('view ' . static::$key)) {
            return true;
        }

        return false;
    }

    /*
     * @param User $user
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view ' . static::$key);
    }
}