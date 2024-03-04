<?php

namespace App\Validation;

use App\MatchingUserAnswer;
use Illuminate\Support\Facades\Log;

class PostalCodeValidator implements MatchingAnswerValidatorInterface
{
    const COUNTRY_QUESTION_ID = 9;
    const COUNTRY_DE_ANSWER_ID = 84;

    protected array $answers = [];

    public function __construct(array $userAnswers)
    {
        $this->answers = $userAnswers ?? [];
    }

    public function validate(array $answer, $userInput, UserAnswerValidationService &$validationService)
    {
        $isQuestion = array_key_exists(self::COUNTRY_QUESTION_ID, $this->answers);
        $isDE = isset($this->answers[self::COUNTRY_QUESTION_ID]) && array_key_exists(self::COUNTRY_DE_ANSWER_ID, $this->answers[self::COUNTRY_QUESTION_ID]);

        if ($isQuestion) {
            if (!is_numeric($userInput) || (!$isDE && strlen($userInput) != 4) || ($isDE && strlen($userInput) != 5)) {
                $code = $isDE ? ValidationError::INVALID_POSTAL_CODE_DE : ValidationError::INVALID_POSTAL_CODE_OTHER;
                $validationService->setLastValidationError(
                    new ValidationError($code)
                );
                return false;
            }
        }
        return true;
    }
}
