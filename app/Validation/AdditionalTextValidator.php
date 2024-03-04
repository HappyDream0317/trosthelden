<?php

namespace App\Validation;

use Illuminate\Support\Facades\Log;

class AdditionalTextValidator implements MatchingAnswerValidatorInterface
{
    public function validate(array $answer, $userInput, UserAnswerValidationService &$validationService)
    {
        if (! $answer['additional_text']) {
            return true;
        }

        if ($answer['obligatory']) {
            if (is_bool($userInput)) {
                $validationService->setLastValidationError(
                    new ValidationError(ValidationError::ADDITIONAL_TEXT_MANDATORY, 'A additonal text must be provided')
                );

                return false;
            }

            $length = strlen($userInput);
            if ($length <= 0 || $length > $answer['numeric_max']) {
                $validationService->setLastValidationError(
                    new ValidationError(ValidationError::INVALID_TEXT_LENGTH, "The text must be between 0 and {$answer['numeric_max']} characters")
                );

                return false;
            }
        }

        return true;
    }
}
