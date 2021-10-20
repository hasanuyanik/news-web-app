<?php

namespace App\Lib\Logger;

use App\Lib\Logger\Driver\DatabaseLogger;
use App\Lib\Logger\Driver\FileLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

class Logger implements LoggerInterface
{
    protected $driver;
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
                $this->driver = new NullLogger();
            }
        }
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $logger = new static();

        $logger->setUp();

        // a message with brace-delimited placeholder names
        $message = $level.": ".$message;

        $logger->driver->log($level, $this->interpolate($message, $context));
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
