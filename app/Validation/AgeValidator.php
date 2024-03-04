<?php

namespace App\Validation;

use DateTime;

class AgeValidator implements MatchingAnswerValidatorInterface
{
    public function validate(array $answer, $userInput, UserAnswerValidationService &$validationService)
    {
        // Type date must be put in as array
        if (! is_array($userInput)) {
            $validationService->setLastValidationError(
                new ValidationError(ValidationError::INVALID_INPUT, 'Date must be provided as array')
            );

            return false;
        }

        $dateOfBirth = new DateTime($userInput['year'].'-'.($userInput['month'] ?: 1).'-'.($userInput['day'] ?: 1));
        $age = (int) (new DateTime())->diff($dateOfBirth)->format('%y');

        if ($age < $answer['numeric_min']) {
            $validationService->setLastValidationError(
                new ValidationError(ValidationError::TOO_YOUNG, "Age is below {$answer['numeric_min']}")
            );

            return false;
        }

        return true;
    }
}
