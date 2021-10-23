<?php

namespace App\Controller;

use App\Lib\Auth\AuthService;
use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Permission\Permission;
use App\Lib\Permission\PermissionRepository;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;
use App\Lib\User\UserWiper;
use App\Lib\Validation;

class AuthController extends BaseController
{

    public function login()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $UserController = new UserController();
            $Validation = new Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $password = ($jsonData["password"]) ? $jsonData["password"] : null;

            $UserController->UsernameValidation($username);
            $UserController->PasswordValidation($password);

            $Validation->ValidationErrorControl($UserController->validationErrors);

            $UserVM = new UserVM();
            $UserVM->username = $username;
            $UserVM->password = $password;
            $UserVM->token = "";

            $UserAuth = new AuthService();

            $UserAuth->login($UserVM);
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

            $UserAuth = new AuthService();
            $result = $UserAuth->logout($UserVM);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);
            }

            echo json_encode($result);
        }
    }

    public function SessionControl()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $UserController = new UserController();
            $User = new User();
            $UserRepository = new UserRepository();

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }


            $User->username = $username;
            $resultForId = $UserRepository->getUsers($User);

            $User->id = $resultForId[0]["id"];

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_id = $User->id;
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            echo json_encode(["message" => "Session Active"]);
        }
    }

    public function permissionControl($UserName, $PermissionName)
    {
        $User = new User();
        $User->username = $UserName;

        $UserRepository = new UserRepository();
        $GetUser = $UserRepository->getUsers($User,0);

        $User->id = $GetUser[0]["id"];

        $Permission = new Permission();
        $Permission->name = $PermissionName;

        $PermissionRepository = new PermissionRepository();
        $GetPermissionInfo = $PermissionRepository->getPermissions(0, $Permission);

        $Permission->id = $GetPermissionInfo[0]["id"];

        $AuthService = new AuthService();

        $result = $AuthService->UserPermissionControl($User, $Permission);

        if ($result == 0)
        {
            header('Content-Type: application/json; charset=utf-8', response_code: 401);

            echo json_encode(["message" => "Unauthorized"]);

            exit;
        }
    }
}