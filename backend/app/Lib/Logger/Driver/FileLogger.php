<?php

namespace App\Lib\Logger\Driver;

use App\Lib\FileManager\FileManager;

class FileLogger implements LogDriverI
{
    private $logFile;
    public function setUp(): void
    {
        $this->logFile = "logger.log";
    }

    public function logMessage(int $level, string $message): void
    {
        $created_at = date("Y-m-d H:i:s");

        $logText = $level." ".$created_at." ".$message.PHP_EOL;

        $FileManager = new FileManager();
        $FileManager->putContentFile($this->logFile,"Log",$logText);
    }

    public function tearDown(): void
    {
        $this->logFile = null;
    }
}