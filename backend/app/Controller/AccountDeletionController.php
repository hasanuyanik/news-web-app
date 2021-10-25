<?php
namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserWiper;

class AccountDeletionController extends BaseController
{
    public function getRequest(int $page, int $status)
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
            $resultForId = $UserRepository->findUser($User);

            $User->id = $resultForId[0]["id"];

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_id = $resultForId[0]["id"];
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            $AuthController = new AuthController();
            $AuthController->permissionControl($username, "UserDelete");

            $UserWiper = new UserWiper();
            header('Content-Type: application/json; charset=utf-8',response_code: 201);

            $result = [
                "content" => $UserWiper->getRequests($status,$page)
            ];

            echo json_encode($result);
        }
    }

    public function findRequest()
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
            $resultForId = $UserRepository->findUser($User);

            $User->id = $resultForId["id"];

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_id = $resultForId["id"];
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            $UserWiper = new UserWiper();
            $GetRequest = $UserWiper->findRequest($User->id);

            if ($GetRequest)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                $result = [
                    "DeleteRequest" => 1
                ];
                echo json_encode($result);

                exit;
            }

            $result = [
                "DeleteRequest" => 0
            ];
            echo json_encode($result);
        }
    }

    public function addRequest()
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
            $resultForId = $UserRepository->findUser($User);

            $User->id = $resultForId["id"];

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_id = $resultForId["id"];
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            $result = null;

            $UserWiper = new UserWiper();
            $GetRequest = $UserWiper->findRequest($User->id);

            if (!$GetRequest)
            {
                $result = $UserWiper->add($User->id);
            }
            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode($result);

                exit;
            }

            echo json_encode($result);
        }
    }

    public function deleteRequest()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $UserController = new UserController();
            $AuthUser = new User();
            $UserRepository = new UserRepository();

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $authUsername = ($jsonData["authUser"]) ? $jsonData["authUser"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }


            $AuthUser->username = $authUsername;
            $resultForId = $UserRepository->findUser($AuthUser);

            $AuthUser->id = $resultForId["id"];

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_id = $AuthUser->id;
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            $User = new User();
            $User->username = $username;
            $resultForId = $UserRepository->getUsers($User);

            $User->id = $resultForId[0]["id"];

            $UserWiper = new UserWiper();
            $GetRequest = $UserWiper->findRequest($User->id);

            $result = $UserWiper->delete($GetRequest[0]["id"]);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode($result);

                exit;
            }

            echo json_encode($result);
        }
    }

    public function userDelete()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $UserController = new UserController();
            $AuthUser = new User();
            $UserRepository = new UserRepository();

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $authUsername = ($jsonData["authUser"]) ? $jsonData["authUser"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $AuthUser->username = $authUsername;
            $resultForId = $UserRepository->getUsers($AuthUser);

            $AuthUser->id = $resultForId[0]["id"];

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_id = $AuthUser->id;
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            $User = new User();
            $User->username = $username;
            $resultForId = $UserRepository->getUsers($User);

            $User->id = $resultForId[0]["id"];

            $AuthController = new AuthController();
            $AuthController->permissionControl($authUsername, "UserDelete");

            $tokenToBeDelete = new Token();
            $tokenToBeDelete->resource_id = $User->id;
            $tokenToBeDelete->resource_type = "user";

            $tokenRepository->delete($tokenToBeDelete);
            $result = $UserRepository->delete($User);

            $UserWiper = new UserWiper();
            $result = $UserWiper->edit($User->id, 1);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode($result);

                exit;
            }

            echo json_encode($result);
        }

    }
}