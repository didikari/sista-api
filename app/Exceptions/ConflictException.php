<?php

namespace App\Exceptions;

use Exception;

class ConflictException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message = "Conflict occurred", $code = 409)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
