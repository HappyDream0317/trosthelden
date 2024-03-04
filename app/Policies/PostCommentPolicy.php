<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class PostCommentPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'post_comment';
}