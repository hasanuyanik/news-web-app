<?php
namespace App\Lib\Auth\Token;

class TokenRepository
{
    public mixed $resource_id;
    public string $resource_type;
    public string $token;
    public $created_at;
}