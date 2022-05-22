<?php

namespace shark\kilytephp\exception;

use Exception;

class NotFoundException extends Exception
{
    protected $message = 'Page not found';
    protected $code = 404;
}