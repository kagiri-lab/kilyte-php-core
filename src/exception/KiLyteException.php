<?php

namespace kilyte\exception;

use Exception;

class KiLyteException extends Exception
{

    protected $code = 500;
    protected $message = "Connection Error";

    public function __construct($message = null, $code = 500)
    {
        if ($message)
            $this->message = $message;
        $this->code = $code;
    }
}
