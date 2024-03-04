<?php

namespace App\Payment;

class BillwerkNoCustomerIdError extends \Exception
{
    protected $code;
    protected $message;

    public function __construct($message = '', $code = 500)
    {
        $this->message = 'No Customer Id provided in ' . $message;
        $this->code = $code;
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
