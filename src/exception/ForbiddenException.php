<?php

namespace kilyte\exception;

use Exception;

class ForbiddenException extends Exception
{

    protected $code = 403;
    protected $message = "UnAuthorized Access";
}
