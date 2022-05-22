<?php

namespace shark\kilytephp\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}