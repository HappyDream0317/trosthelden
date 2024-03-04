<?php

namespace App\Validation;

use JsonSerializable;

class ValidationError implements JsonSerializable
{
    public const TOO_YOUNG = 1;
    public const INVALID_INPUT = 2;
    public const ADDITIONAL_TEXT_MANDATORY = 3;
    public const INVALID_TEXT_LENGTH = 4;
    public const INVALID_NUMERIC_VALUE = 5;
    public const INVALID_POSTAL_CODE_DE = 6;
    public const INVALID_POSTAL_CODE_OTHER = 7;

    protected int $code;
    protected string $msg;

    public function __construct(int $code, string $msg = '')
    {
        $this->code = $code;
        $this->msg = $msg;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    public function jsonSerialize()
    {
        return ['msg' => $this->msg, 'code' => $this->code];
    }
}
