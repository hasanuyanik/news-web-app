<?php

namespace App\Lib\Logger;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Logger\Driver\DatabaseLogger;
use App\Lib\Logger\Driver\FileLogger;
use App\Lib\Logger\Driver\LogDriverI;

class Logger implements LoggerInterface
{
    protected LogDriverI $driver;
    private function setUp(): void
    {
        $configdir = __DIR__."/../../../config.php";

        if (file_exists($configdir))
        {
            $config = require $configdir;
            $logging = $config["logging"];

            if ($logging == "database")
            {
                $this->driver = new DatabaseLogger();
                $this->driver->setUp();
            }
            elseif ($logging == "file")
            {
                $this->driver = new FileLogger();
                $this->driver->setUp();
            }
            elseif ($logging == "null")
            {
                die("Loglama Kapalı");
            }
        }
    }

    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $logger = new static();

        $logger->setUp();


        // a message with brace-delimited placeholder names
        $message = $level.": ".$message;

        var_dump($context);

        $logger->driver->logMessage($level, $this->interpolate($message, $context));
        $logger->tearDown();
    }

    private function tearDown(): void
    {
        $this->driver->tearDown();
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