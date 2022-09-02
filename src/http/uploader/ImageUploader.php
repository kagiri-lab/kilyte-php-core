<?php

namespace kilyte\http\uploader;

use kilyte\Application;

class ImageUploader extends Uploader
{

    public static function execute()
    {
        if (isset($_FILES["file"])) {

            $upload = new Uploader($_FILES["file"]);
            $upload->must_be_image();
            $upload->max_size(10000); // in MB
            $upload->path(Application::$ROOT_DIR.'\storage\images\\');
            $upload->encrypt_name();
            $upload->upload();
            return $upload;
        }
    }
}
