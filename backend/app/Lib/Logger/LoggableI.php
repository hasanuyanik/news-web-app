<?php

namespace App\Lib\Logger;

interface LoggableI
{
   public function setUp(): void;
   public static function log(int $level, string $message): void;
   public function tearDown(): void;
}