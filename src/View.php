<?php

namespace kilyte;


class View
{
    public string $title = '';

    public function renderView(array $params = [], $view = null)
    {
        if (Application::$app->router->isAPI) {
            header("Content-type: application/json; charset=utf-8");
            if (is_array($params))
                return json_encode($params);
            return $params;
        } else {
            $layoutName = Application::$app->layout;
            if (Application::$app->controller) {
                $layoutName = Application::$app->controller->layout;
            }
            $viewContent = $this->renderViewOnly($params, $view);
            ob_start();
            include_once Application::$ROOT_DIR . "/views/layouts/$layoutName.php";
            $site_name = "KiLyte";
            $site_url = "http://localhost/";
            if (isset($_ENV)) {
                if (isset($_ENV['SITE_NAME']))
                    $site_name = $_ENV['SITE_NAME'];
                if (isset($_ENV['SITE_URL']))
                    $site_url = $_ENV['SITE_URL'];
            }
            print_r($site_url);
            $layoutContent = ob_get_clean();
            $layoutContent = str_replace('{{content}}', $viewContent, $layoutContent);
            $layoutContent = str_replace('{{site-name}}', $site_name, $layoutContent);
            return str_replace('{{site-url}}', $site_url, $layoutContent);
        }
    }

    public function renderViewOnly(array $params, $view)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        $view = str_replace('.', '/', $view);
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
