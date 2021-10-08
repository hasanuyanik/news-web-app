<?php
namespace App\Lib\User;

use Cassandra\Date;

class UserRepository
{
    public mixed  $id = null;
    public string $username = "";
    public string $fullname = "";
    public string $password = "";
    public string $email = "";
    public string $phone = "";
}