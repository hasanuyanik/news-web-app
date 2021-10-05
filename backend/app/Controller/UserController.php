<?php

namespace App\Controller;

class UserController extends BaseController
{
    public function index()
    {
        echo "INDEX USERCONTROLLER";
    }

    public function show(string $name): void
    {
        echo "User:{$name}";
    }
}