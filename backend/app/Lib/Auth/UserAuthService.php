<?php
namespace App\Lib\Auth;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Encoder\Encoder;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;

class UserAuthService implements AuthServiceI
{

    public function logout(UserVM $user): string
    {
        $tokenRepository = new TokenRepository();
        $tokenRepository->token = $user->token;

        $tokenController = new Token();
        return $tokenController->delete($tokenRepository);
    }

    public function login(UserVM $user): mixed
    {
        $tokenRepository = new TokenRepository();
        $tokenRepository->resource_type = "user";
        $tokenRepository->token = $user->token;
        $tokenRepository->created_at = date('Y.m.d H:i:s');

        $encoder = new Encoder();
        $password = $encoder->salt($encoder->encode($user->password));

        $UserRepository = new UserRepository();
        $UserRepository->username = $user->username;
        $UserRepository->password = $password;

        $UserController = new User();
        $UserControl = $UserController->getUsers($UserRepository);

        if (count($UserControl) > 0)
        {
            $tokenRepository->resource_id = $UserControl[0]["id"];

            $tokenController = new Token();
            $newToken = $tokenController->create($tokenRepository);

            $UserControl[0]["password"] = "";
            $UserControl[0]["token"] = $newToken;
            return $UserControl;
        }

        return false;
    }
}