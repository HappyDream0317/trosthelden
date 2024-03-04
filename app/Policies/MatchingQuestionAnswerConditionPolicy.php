<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class MatchingQuestionAnswerConditionPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'matching_question_answer_condition';
}