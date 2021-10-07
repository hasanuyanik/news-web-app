<?php

namespace App\Lib\Logger\Driver;

interface LogDriverI
{
    public function setUp(): void;
    public function logMessage($level, string $message): void;
    public function tearDown(): void;
}