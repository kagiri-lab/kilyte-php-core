<?php

namespace kilyte\http;

use Exception;
use kilyte\Application;

class Uploader
{
    public static function upload()
    {
        self::validateFile();
        $location = self::storageDirectory();
        $filename = $_FILES["file"]["name"];
        if (is_array($filename))
            $filename = $filename[0];
        $filetype = $_FILES["file"]["type"];
        $filesize = $_FILES["file"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $title = basename($filename, "." . $ext);
        $filename = md5($filename . time()) . '.' . strtolower($ext);
        $file = array(
            "title" => $title,
            "name" => $filename,
            "type" => $filetype,
            "size" => $filesize,
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

    static function allowedExtensions()
    {

        $allowed = array(
            "jpg" => "image/jpg",
            "jpeg" => "image/jpeg",
            "gif" => "image/gif",
            "png" => "image/png",
            "mov" => "video/mov",
            "mp4" => "video/mp4",
            "3gp" => "video/3gp",
            "ogg" => "video/ogg",
            "flv" => "video/x-flv",
            "mov" => "video/quicktime",
            "avi" => "video/x-msvideo",
            "wmv" => "video/x-msvideo",
            "m3u8" => "application/x-mpegURL",
            "ts" => "video/MP2T",
            "mp3" => "audio/mpeg",
            "wav" => "audio/mav",
            "pdf" => "application/pdf",
            "doc" => "application/doc",
            "docx" => "application/docx",
            "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "rtf" => "application/rtf",
            "txt" => "text/plain",
            "odf" => "application/odf",
            "xls" => "application/xls",
            "xlsx" => "application/xlsx",
            "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "msword" => "application/msword",
            "zip" => "application/zip",
            "rar" => "application/x-rar-compressed",
            "octet-stream" => "application/octet-stream"
        );

        return $allowed;
    }

    static function validateFile()
    {
        if (empty($_FILES["file"]))
            throw new Exception('File not set');

        if (!empty($_FILES["file"]["error"]))
            switch ($_FILES["file"]["error"]) {
                case 4:
                    throw new Exception('No file was uploaded');
                    break;
                default:
                    throw new Exception('File upload error');
                    break;
            }

        $filename = $_FILES["file"]["name"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!empty($ext))
            $ext = strtolower($ext);
        if (!array_key_exists($ext, self::allowedExtensions()))
            throw new Exception('Invalid file format.');
    }
}
