<?php

namespace App\Lib\User;

interface UserI
{
    public function getUsers(?UserRepository $user): array;
    public function add(UserRepository $user): string;
    public function edit(UserRepository $user): string;
    public function delete(UserRepository $user): string;
}