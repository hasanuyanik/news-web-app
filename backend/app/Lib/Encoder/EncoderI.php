<?php

namespace App\Lib\Encoder;

interface EncoderI
{
    public function encode(mixed $data): string;
    public function salt(mixed $data): string;
}