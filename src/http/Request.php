<?php

namespace kilyte\Http;

class Request
{
    private array $routeParams = [];

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isWriteRequest(): bool
    {
        if (in_array($this->getMethod(), ["POST", 'PUT', 'PATCH', "DELETE"])) {
            return true;
        }
        return false;
    }

    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    public function get($get = null)
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if (!empty($get)) {
            if (key_exists($get, $data))
                return $data[$get];
        } else
            return $data;
    }

    public function setRouteParams($params)
    {
        $this->routeParams = $params;
        return $this;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    public function getRouteParam($param, $default = null)
    {
        return $this->routeParams[$param] ?? $default;
    }

    public function getHeader($get = null)
    {
        $data = [];
        foreach ($_SERVER as $key => $value)
            $data[$key] = filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        if (!empty($get)) {
            if (key_exists($get, $data))
                return $data[$get];
        } else
            return $data;
    }

    public function getJsonData($get = null)
    {
        $data = [];
        $content = file_get_contents('php://input');
        if (!empty($content)) {
            $content = json_decode($content);
            foreach ($content as $key => $value)
                $data[$key] = $value;
            if (!empty($get)) {
                if (key_exists($get, $data))
                    return $data[$get];
            } else
                return $data;
        }
    }
}
