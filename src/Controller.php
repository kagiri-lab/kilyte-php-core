<?php

namespace kilyte;

use kilyte\database\Database;
use kilyte\Http\Request;
use kilyte\http\Response;
use kilyte\middlewares\BaseMiddleware;

class Controller
{

    public string $layout = 'layout';
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

    public function request(): Request
    {
        return Application::$app->request;
    }

    public function response(): Response
    {
        return Application::$app->response;
    }

    public function db(): Database
    {
        return Application::$app->db;
    }

    public function env(): array
    {
        return $_ENV;
    }

    public function user()
    {
        return Application::$app->user;
    }
}
