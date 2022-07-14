<?php

namespace kilyte\exception;

use Exception;

class KiLyteException extends Exception
{

    protected $code = 500;
    protected $message = "Connection Error";
    protected $line = 0;
    protected $file = "";
    protected $string = "";

    public function __construct($message = null, $code = 500, $line = 0, $file = null, $string = null)
    {
        if ($message)
            $this->message = $message;
        $this->code = $code;
        $this->line = $line;
        if ($file)
            $this->file = $file;
        if ($string)
            $this->string = $string;
    }
}
