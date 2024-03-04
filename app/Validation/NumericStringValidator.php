<?php

namespace App\Validation;

use App\MatchingUserAnswer;

class NumericStringValidator implements MatchingAnswerValidatorInterface
{
    public function validate(array $answer, $userInput, UserAnswerValidationService &$validationService)
    {
        if (! is_numeric($userInput)) {
            $validationService->setLastValidationError(
                new ValidationError(ValidationError::INVALID_INPUT)
            );

            return false;
        }
        $userInput = (int) $userInput;
        if ($userInput < $answer['numeric_min'] || $userInput > $answer['numeric_max']) {
            $validationService->setLastValidationError(
                new ValidationError(ValidationError::INVALID_NUMERIC_VALUE)
            );

            return false;
        }

        return true;
    }
}
