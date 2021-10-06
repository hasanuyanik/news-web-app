<?php

namespace App\Lib\FileManager;


class FileManager implements FileManagerI
{
    private $config;
    private $storage;

    public function __construct()
    {
        $configdir = __DIR__."/../../../config.php";
        if (file_exists($configdir))
        {
            $this->config = require $configdir;
            $this->storage = $this->config["storage"];
        }
    }

    public function uploadFile(array $file, string $path_file): bool
    {
        $target = "../".$this->storage."/".$path_file;
        if (move_uploaded_file($file["tmp_name"], $target))
        {
            return true;
        }
        return false;
    }

    public function deleteFile(string $file): bool
    {
        $targetFile = '../'.$this->storage.'/'.$file;
        if (!unlink($targetFile) || file_exists($targetFile))
        {
            return false;
        }
        return true;
    }

    public function putContentFile(string $file, string $path_file, string $putContent): void
    {
        $target = "../".$this->storage."/".$path_file."/".$file;
        file_put_contents($target,$putContent,FILE_APPEND);
    }

}