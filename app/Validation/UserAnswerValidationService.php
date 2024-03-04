<?php

namespace App\Validation;

use App\MatchingAnswer;
use Illuminate\Support\Facades\Log;

class UserAnswerValidationService
{
    const POSTALCODE_VALIDATOR_ID = 4;

    protected array $validatorFactories = [];
    protected array $validators = [];
    protected $lastError = null;

    public function __construct()
    {
        $this->addValidator(1, function () {
            return new NumericStringValidator();
        });
        $this->addValidator(2, function () {
            return new AdditionalTextValidator();
        });
        $this->addValidator(3, function () {
            return new AgeValidator();
        });
        //** id 4 is reserved for PostalCodeValidator */
    }

    public function addValidator(int $id, callable $validatorFactory): self
    {
        $this->validatorFactories[$id] = $validatorFactory;

        return $this;
    }

    public function getValidator(int $id): MatchingAnswerValidatorInterface
    {
        if (! array_key_exists($id, $this->validators)) {
            $this->validators[$id] = $this->validatorFactories[$id]();
        }

        return $this->validators[$id];
    }

    public function __invoke(array $answer, $userInput): bool
    {
        if ($answer['matching_answer_validation_id'] === null) {
            return true;
        }

        return $this->getValidator($answer['matching_answer_validation_id'])
            ->validate($answer, $userInput, $this);
    }

    public function setLastValidationError(ValidationError $err): self
    {
        $this->lastError = $err;

        return $this;
    }

    public function getLastValidationError(): ?ValidationError
    {
        if ($this->lastError !== null) {
            return $this->lastError;
        }

        return null;
    }

    public function resetValidationError()
    {
        $this->lastError = null;
    }

    public function hasError()
    {
        return $this->lastError !== null;
    }
}
