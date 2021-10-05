<?php

interface UserI
{
    public function getUsers(?UserI $user): array;
    public function add(User $user): string;
    public function edit(User $user): string;
    public function delete(User $user): string;
}