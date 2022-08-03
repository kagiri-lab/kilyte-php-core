<?php

namespace kilyte\resources;

class HTML
{

    public static function start()
    {
        echo "    <!DOCTYPE html> \n    ";
        echo "<html lang='en-US' dir='ltr'> \n    ";
    }

    public static function end()
    {
        echo "</html>";
    }

    public static function title($text)
    {
        echo "<title>$text</title> \n    ";
    }

    public static function Meta(array $metas)
    {
        if (!empty($metas))
            foreach ($metas as $meta) {
                $mt = "<meta";
                if (key_exists('name', $meta))
                    $mt = $mt . ' name="' . $meta['name'] . '"';
                if (key_exists('http', $meta))
                    $mt = $mt . ' http-equiv="' . $meta['http'] . '"';
                if (key_exists('content', $meta))
                    $mt = $mt . ' content="' . $meta['content'] . '"';
                if (key_exists('charset', $meta))
                    $mt = $mt . ' charset="' . $meta["charset"] . '"';
                echo $mt . "> \n    ";
            }
    }

    public static function Link(array $links)
    {
        if (!empty($links)) {
            foreach ($links as $link) {
                $ln = "<link";
                if (key_exists('rel', $link))
                    $ln = $ln . ' rel="' . $link["rel"] . '"';
                if (key_exists('type', $link))
                    $ln = $ln . ' type="' . $link["type"] . '"';
                if (key_exists('href', $link))
                    $ln = $ln . ' href="' . $link['href'] . '"';
                if (key_exists('id', $link))
                    $ln = $ln . ' id="' . $link['id'] . '"';
                if (key_exists('sizes', $link))
                    $ln = $ln . ' sizes="' . $link['sizes'] . '"';
                echo $ln . "/> \n    ";
            }
        }
    }

    public static function Script(array $scripts)
    {
        if (!empty($scripts)) {
            foreach ($scripts as $script)
                echo "<script src='$script'></script> \n    ";
        }
    }

    public static function header_start()
    {
        echo "<head> \n    ";
    }

    public static function header_end()
    {
        echo "</head> \n    ";
    }

    public static function body_start()
    {
        echo "<body> \n    ";
    }

    public static function body_end()
    {
        echo "</body> \n    ";
    }
}
