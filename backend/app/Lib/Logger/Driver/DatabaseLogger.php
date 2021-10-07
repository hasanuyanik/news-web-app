<?php

namespace App\Lib\Logger\Driver;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Database\DatabaseFactoryI;

class DatabaseLogger implements LogDriverI
{
    private $driver;
    public function setUp(): void
    {
        $this->driver = (new DatabaseFactory())->db;
    }

    public function logMessage($level, string $message): void
    {
        $created_at = date('Y-m-d H:i:s');
        $this->driver->add("logs",
            [
                "level" => $level,
                "message" => $message,
                "created_at" => $created_at
            ]);
    }

    public function tearDown(): void
    {
       $this->driver = null;
    }
}