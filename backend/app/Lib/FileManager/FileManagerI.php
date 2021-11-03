<?php

namespace App\Lib\FileManager;

interface FileManagerI
{
    public function uploadFile(array $file, string $path_file): bool;
    public function deleteFile(string $file): bool;
    public function putContentFile(string $file, string $path_file, string $putContent): bool;
}