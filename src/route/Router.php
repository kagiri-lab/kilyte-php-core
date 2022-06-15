<?php

namespace kilyte\route;

use kilyte\Application;
use kilyte\exception\NotFoundException;
use kilyte\http\Request;
use kilyte\http\Response;
use kilyte\middlewares\AuthMiddleware;
use Throwable;

class Router
{
    private Request $request;
    private Response $response;
    private array $routeMap = [];
    public bool $isAPI = false;
    private array $route_middlewares = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($callback, array $paths, $route = null, $auth = null)
    {
        foreach ($paths as $url => $func) {
            $this->addMiddleWareArray($func, $auth);
            $this->routeMap['get'][trim($route) . trim($url)] = [$callback, $func];
        }
    }

    public function post($callback, array $paths, $route = null, $auth = null)
    {
        foreach ($paths as $url => $func) {
            $this->addMiddleWareArray($func, $auth);
            $this->routeMap['post'][trim($route) . trim($url)] = [$callback, $func];
        }
    }

    private function addMiddleWareArray($url, $auth)
    {
        if ($auth === 'auth')
            $this->route_middlewares[] = $url;
    }

    public function getRouteMap($method): array
    {
        return $this->routeMap[$method] ?? [];
    }

    public function getCallback()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $url = trim($url, '/');
        $routes = $this->getRouteMap($method);
        $routeParams = false;
        foreach ($routes as $route => $callback) {
            $route = trim($route, '/');
            $routeNames = [];
            if (!$route) {
                continue;
            }
            if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }
            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn ($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";
            if (preg_match_all($routeRegex, $url, $valueMatches)) {
                $values = [];
                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }
                $routeParams = array_combine($routeNames, $values);
                $this->request->setRouteParams($routeParams);
                return $callback;
            }
        }

        return false;
    }

    public function resolve()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $callback = $this->routeMap[$method][$url] ?? false;
        $exURL = explode('/', $url);
        if (count($exURL) > 1) {
            $api = strtolower($exURL[1]);
            if ($api === 'api')
                $this->isAPI = true;
        }
        if (!$callback) {

            $callback = $this->getCallback();
            if ($callback === false) {
                $this->response->statusCode(404);
                throw new NotFoundException();
            }
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {

            $controller = new $callback[0];
            $controller->action = $callback[1];
            Application::$app->controller = $controller;
            if ($this->route_middlewares)
                $controller->registerMiddleware(new AuthMiddleware($this->route_middlewares));
            $middlewares = $controller->getMiddlewares();
            foreach ($middlewares as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($params = [], $view = null)
    {
        return Application::$app->view->renderView($params, $view);
    }

    public function renderViewOnly($params = [], $view = null)
    {
        return Application::$app->view->renderViewOnly($params, $view);
    }

    public function renderError($view, Throwable $e)
    {
        http_response_code($e->getCode());
        if ($this->isAPI) {
            return Application::$app->view->renderView([
                "type" => get_class($e),
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "code" => $e->getCode()
            ]);
        } else {
            return Application::$app->view->renderView([
                "exception" => $e
            ], $view);
        }
    }
}
