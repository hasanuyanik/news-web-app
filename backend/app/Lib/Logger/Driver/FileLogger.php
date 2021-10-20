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

    public function log($level, string $message): void
    {
        $created_at = date("Y-m-d H:i:s");

        $logText = $created_at." ".$message.PHP_EOL;

        $FileManager = new FileManager();
        $FileManager->putContentFile($this->logFile,"Log",$logText);
    }

    public function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}