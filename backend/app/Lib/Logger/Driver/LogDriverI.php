<?php

namespace App\Lib\Logger\Driver;

interface LogDriverI
{
    public function setUp(): void;
    public function log($level, string $message): void;
}