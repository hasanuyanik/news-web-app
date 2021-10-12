<?php
namespace App\Lib\Auth;

use App\Lib\User\UserVM;

interface AuthServiceI
{
    public function logout(UserVM $user): string;
    public function login(UserVM $user): mixed;
}