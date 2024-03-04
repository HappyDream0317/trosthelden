<?php

namespace App\Exceptions;

use Exception;
use JsonSerializable;

class MissingAnswersException extends Exception implements JsonSerializable
{
    private $missingQuestionIds = [];
    private $questionValidationErrors = [];

    public function addMissingQuestionIds(array $missingQuestionIds): self
    {
        $this->missingQuestionIds = $missingQuestionIds;

        return $this;
    }

    public function getMissingQuestionIds()
    {
        return $this->missingQuestionIds;
    }

    public function addValidationErrors(array $questionValidationErrors): self
    {
        $this->questionValidationErrors = $questionValidationErrors;

        return $this;
    }

    public function getValidationErrors(): array
    {
        return $this->questionValidationErrors;
    }

    public function jsonSerialize()
    {
        return [
            'missingQuestionIds' => $this->missingQuestionIds,
            'validationErrors' => $this->questionValidationErrors,
        ];
    }
}
