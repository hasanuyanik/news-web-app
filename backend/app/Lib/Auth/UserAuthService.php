<?php
namespace App\Lib\Auth;

use App\Controller\UserController;
use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Encoder\Encoder;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;

class UserAuthService
{

    public function logout(UserVM $user): string
    {
        $token = new Token();
        $token->token = $user->token;

        $tokenRepository = new TokenRepository();
        return $tokenRepository->delete($token);
    }

    public function login(UserVM $user): void
    {
        $token = new Token();
        $token->resource_type = "user";
        $token->token = $user->token;
        $token->created_at = date('Y.m.d H:i:s');

        $encoder = new Encoder();
        $password = $encoder->salt($encoder->encode($user->password));

        $User = new User();
        $User->username = $user->username;
        $User->password = $password;

        $UserRepository = new UserRepository();
        $UserControl = $UserRepository->getUsers($User);

        if (count($UserControl) > 0)
        {
            $token->resource_id = $UserControl[0]["id"];

            $tokenRepository = new TokenRepository();
            $newToken = $tokenRepository->create($token);

            $UserControl[0]["password"] = "";
            $UserControl[0]["token"] = $newToken;

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            echo json_encode($UserControl);

            return;
        }

        $UserController = new UserController();

        echo json_encode($UserController->errors);
    }
}