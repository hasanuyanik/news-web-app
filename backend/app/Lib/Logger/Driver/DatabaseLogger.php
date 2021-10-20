<?php

namespace App\Lib\Logger\Driver;

use App\Lib\Database\DatabaseFactory;

class DatabaseLogger implements LogDriverI
{
    private $driver;
    public function setUp(): void
    {
        $this->driver = (new DatabaseFactory())->db;
    }

    public function log($level, string $message): void
    {
        $created_at = date('Y-m-d H:i:s');
        $this->driver->add("logs",
            [
                "level" => $level,
                "message" => $message,
                "created_at" => $created_at
            ]);
    }

}