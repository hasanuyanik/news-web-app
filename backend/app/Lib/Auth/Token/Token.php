<?php
namespace App\Lib\Auth\Token;

class Token
{
    public mixed $resource_id = null;
    public string $resource_type = "";
    public string $token = "";
    public $created_at;
}