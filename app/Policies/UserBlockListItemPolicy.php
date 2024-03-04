<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserBlockListItemPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'user_block_list_item';
}