<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class MatchingAnswerTypePolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'matching_answer_type';
}