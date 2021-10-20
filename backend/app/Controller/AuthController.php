<?php

namespace App\Controller;

use App\Lib\Auth\UserAuthService;
use App\Lib\User\UserVM;

class AuthController extends BaseController
{

    public function login()
    {
        $UserController = new UserController();

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