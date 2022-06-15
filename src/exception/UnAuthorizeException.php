<?php


namespace kilyte\exception;

use Exception;

class UnAuthorizeException extends Exception
{

    protected $code;
    protected $message;

    public function __construct(string $message = "UnAuthorize Access", int $code = 401)
    {
        $this->code = $code;
        $this->message = $message;
    }
}
