<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class MatchingQuestionStepContentPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'matching_question_step_content';
}