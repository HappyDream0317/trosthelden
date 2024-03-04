<?php

namespace App\Validation;

use App\MatchingUserAnswer;

interface MatchingAnswerValidatorInterface
{
    public function validate(array $answer, $userInput, UserAnswerValidationService &$validationService);
}
