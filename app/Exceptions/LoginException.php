<?php

namespace App\Exceptions;

use \Exception;

class LoginException extends Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message);
    }

    public function __toString()
    {
        return $this->getMessage();
    }
}
