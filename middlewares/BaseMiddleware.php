<?php

namespace kilyte\kilytephp\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}