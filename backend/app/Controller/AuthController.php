<?php

namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Auth\UserAuthService;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;
use App\Lib\User\UserWiper;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;

class AuthController extends BaseController
{

    public function login()
    {
        $UserController = new UserController();
        $user = new User();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $password = ($jsonData["password"]) ? $jsonData["password"] : null;

            $UserController->UsernameValidation($username);
            $UserController->PasswordValidation($password);

            if ($UserController->validationErrors)
            {

                $result = [
                    "validationErrors" => $UserController->validationErrors
                ];

                echo json_encode($result);

                exit;
            }

            $UserVM = new UserVM();
            $UserVM->username = $username;
            $UserVM->password = $password;
            $UserVM->token = "";

            $UserAuth = new UserAuthService();
            $result = $UserAuth->login($UserVM);


            if ($result != false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode($result);
                return;
            }

            echo json_encode(["message" => "Unauthorized"]);
        }
    }

    public function logout()
    {
        $user = new User();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $UserVM = new UserVM();
            $UserVM->token = $token;

            $UserAuth = new UserAuthService();
            $result = $UserAuth->logout($UserVM);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);
            }

            echo json_encode($result);
        }
    }
}