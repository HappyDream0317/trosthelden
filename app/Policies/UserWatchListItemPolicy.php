<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserWatchListItemPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'user_watch_list_item';
}