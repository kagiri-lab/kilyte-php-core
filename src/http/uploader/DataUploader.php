<?php

namespace kilyte\http\uploader;

class DataUploader extends Uploader
{
    public static function execute(array $fileType)
    {
        if (isset($_FILES["file"])) {

            $upload = new Uploader($_FILES["file"]);
            $upload->allowed_extensions($fileType);
            $upload->max_size(1000); // in MB
            $upload->min_size(0); // in MB
            $upload->path("storage/files");
            $upload->encrypt_name();
            $upload->upload();
            return $upload;
        }
    }
}
