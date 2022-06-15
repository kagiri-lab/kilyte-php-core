<?php

namespace kilyte;

use kilyte\middlewares\BaseMiddleware;

class Controller
{

    public string $layout = 'auth';
    public string $action = '';

    protected array $middlewares = [];

    public function setLayout($layout): void
    {
        $this->layout = $layout;
    }

    public function render($params = [], $view = null): string
    {
        return Application::$app->router->renderView($params, $view);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    
}
