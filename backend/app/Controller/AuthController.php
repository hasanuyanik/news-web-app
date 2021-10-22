<?php

namespace App\Controller;

use App\Lib\Auth\AuthService;
use App\Lib\Permission\Permission;
use App\Lib\Permission\PermissionRepository;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;
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

    public function permissionControl()
    {
        $User = new User();
        $User->username = "username2";

        $UserRepository = new UserRepository();
        $GetUser = $UserRepository->getUsers($User,0);

        $User->id = $GetUser[0]["id"];

        $Permission = new Permission();
        $Permission->name = "NewsPublish";

        $PermissionRepository = new PermissionRepository();
        $GetPermissionInfo = $PermissionRepository->getPermissions(0, $Permission);

        $Permission->id = $GetPermissionInfo[0]["id"];

        $AuthService = new AuthService();

        var_dump($AuthService->UserPermissionControl($User, $Permission));
    }
}