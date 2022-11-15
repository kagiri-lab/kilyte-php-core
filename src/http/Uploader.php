<?php

namespace kilyte\Http;

use Exception;
use kilyte\Application;

class Uploader
{
    public static function upload()
    {
        $location = self::storageDirectory();
        $filename = $_FILES["file"]["name"];
        if (is_array($filename))
            $filename = $filename[0];
        $filetype = $_FILES["file"]["type"];
        $filesize = $_FILES["file"]["size"];
        $maxsize = 1024 * 1024 * 1024;

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $title = basename($filename, "." . $ext);
        $filename = md5($filename . time()) . '.' . strtolower($ext);
        $file = array(
            "title" => $title,
            "name" => $filename,
            "type" => $filetype,
            "size" => $filesize,
            "maxsize" => $maxsize,
            "location" => $location,
            "ext" => $ext
        );
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $location . $file["name"]))
            return $file;
        else
            return [];
    }

    private static function storageDirectory()
    {
        $location = Application::$ROOT_DIR . "/public/storage/";
        if (!file_exists($location)) {
            try {
                mkdir($location);
                file_put_contents($location . 'index.php', '<?php');
            } catch (Exception $ex) {
                throw $ex;
            }
        }
        $location .= date("Y/");
        if (!file_exists($location)) {
            try {
                mkdir($location);
                file_put_contents($location . 'index.php', '<?php');
            } catch (Exception $ex) {
                throw $ex;
            }
        }
        $location .= date("m/");
        if (!file_exists($location)) {
            try {
                mkdir($location);
                file_put_contents($location . 'index.php', '<?php');
            } catch (Exception $ex) {
                throw $ex;
            }
        }
        return $location;
    }
}
