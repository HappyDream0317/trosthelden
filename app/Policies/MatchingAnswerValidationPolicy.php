<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class MatchingAnswerValidationPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'matching_answer_validation';
}