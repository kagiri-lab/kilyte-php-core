<?php

namespace kilyte\exception;

use Exception;

class NotFoundException extends Exception
{

    protected $code = 404;
    protected $message = "Page not found";
}
