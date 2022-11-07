<?php

namespace iutnc\netvod\exception;

use Exception;

class AuthException extends Exception
{

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
    }


}