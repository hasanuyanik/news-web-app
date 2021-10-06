<?php

namespace App\Lib\Logger;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Logger\Driver\DatabaseLogger;
use App\Lib\Logger\Driver\FileLogger;
use App\Lib\Logger\Driver\LogDriverI;

class Logger implements LoggableI
{
    protected LogDriverI $driver;
    public function setUp(): void
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

    public static function log(int $level, string $message): void
    {
        $logger = new static();

        $logger->setUp();
        $logger->driver->logMessage($level, $message);
        $logger->tearDown();
    }

    public function tearDown(): void
    {
        $this->driver->tearDown();
    }
}