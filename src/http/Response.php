<?php

namespace kilyte\http;

class Response
{
    public function statusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function print_response($object)
    {
        if (is_array($object))
            echo json_encode($object);
        if (is_string($object))
            echo $object;
    }
}
