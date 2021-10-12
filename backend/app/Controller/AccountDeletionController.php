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
            $UserWiper = new UserWiper();
            header('Content-Type: application/json; charset=utf-8',response_code: 201);

            $result = [
                "content" => $UserWiper->getRequests($status,$page)
            ];

            echo json_encode($result);
    }

    public function findRequest()
    {

        $user = new User();
        $UserController = new UserController();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);


        if ($jsonData) {
            $userRepository = new UserRepository();


            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $RepoForId = new UserRepository();
            $RepoForId->username = $username;
            $resultForId = $user->getUsers($RepoForId);

            $userRepository->id = $resultForId[0]["id"];

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $tokenRepository->resource_id = $resultForId[0]["id"];
            $tokenRepository->resource_type = "user";

            /* \/ Yetkili kişi dışındakiler için burası */
            /*
            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }
            */
            /* /\ Yetkili kişi dışındakiler için burası */
            $userRepository->password = $resultForId[0]["password"];

            $UserWiper = new UserWiper();
            $GetRequest = $UserWiper->findRequest($userRepository->id);

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
        $user = new User();
        $UserController = new UserController();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $userRepository = new UserRepository();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $RepoForId = new UserRepository();
            $RepoForId->username = $username;
            $resultForId = $user->getUsers($RepoForId);

            $userRepository->id = $resultForId[0]["id"];

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $tokenRepository->resource_id = $resultForId[0]["id"];
            $tokenRepository->resource_type = "user";

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $userRepository->password = $resultForId[0]["password"];

            $result = null;

            $UserWiper = new UserWiper();
            $GetRequest = $UserWiper->findRequest($userRepository->id);

            if (!$GetRequest)
            {
                $result = $UserWiper->add($userRepository->id);
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
        $user = new User();
        $UserController = new UserController();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $userRepository = new UserRepository();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;



            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $RepoForId = new UserRepository();
            $RepoForId->username = $username;
            $resultForId = $user->getUsers($RepoForId);

            $userRepository->id = $resultForId[0]["id"];

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $tokenRepository->resource_id = $resultForId[0]["id"];
            $tokenRepository->resource_type = "user";



            /* \/ Yetkili kişi dışındakiler için burası */
            /*
            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }
            */
            /* /\ Yetkili kişi dışındakiler için burası */
            $UserWiper = new UserWiper();
            $GetRequest = $UserWiper->findRequest($userRepository->id);

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
        $user = new User();
        $UserController = new UserController();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $userRepository = new UserRepository();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $RepoForId = new UserRepository();
            $RepoForId->username = $username;
            $resultForId = $user->getUsers($RepoForId);

            $userRepository->id = $resultForId[0]["id"];

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $tokenRepository->resource_id = $resultForId[0]["id"];
            $tokenRepository->resource_type = "user";

            /* \/ Yetkili kişi dışındakiler için burası */
            /*
            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }
            */
            /* \/ Yetkili kişi dışındakiler için burası */

            $result = $user->delete($userRepository);

            $UserWiper = new UserWiper();

            $result = $UserWiper->edit($userRepository->id, 1);

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