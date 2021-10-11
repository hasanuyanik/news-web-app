<?php
namespace App\Lib\Auth\Token;

interface TokenI
{
    public function tokenControl(TokenRepository $token): bool;
    public function create(TokenRepository $token): string;
    public function delete(TokenRepository $token): string;
    public function tokenGenerator(): string;
}