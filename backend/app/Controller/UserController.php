<?php

namespace App\Controller;

use App\Lib\Activity\Activity;
use App\Lib\Auth\UserAuthService;
use App\Lib\Database\DatabaseFactory;
use App\Lib\Encoder\Encoder;
use App\Lib\FileManager\FileManager;
use App\Lib\Logger\Logger;
use App\Lib\Logger\LogLevel;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;
use App\Lib\User\UserWiper;
use http\Env\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;

class UserController extends BaseController
{
    public function getUsers(int $page)
    {
        $user = new User();
        $userRepository = new UserRepository();
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = [
            "content" => $user->getUsers($userRepository)
        ];

        echo json_encode($result);

    }

    public function add()
    {
        $user = new User();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $fullname = ($jsonData["fullname"]) ? $jsonData["fullname"] : null;
            $email = ($jsonData["email"]) ? $jsonData["email"] : null;
            $phone = ($jsonData["phone"]) ? $jsonData["phone"] : null;
            $password = ($jsonData["password"]) ? $jsonData["password"] : null;


            $validationErrors = [];


            $validator = Validation::createValidator();
            $violations = $validator->validate($username, [
                new Length(['min' => 6,'max' => 50]),
                new NotNull(),
                new Required()
            ]);

            if (0 !== count($violations)) {

                foreach ($violations as $violation)
                {
                    $validationErrors["username"] = $violation->getMessage();
                }

            }

            $violations = $validator->validate($fullname, [
                new Length(['min' => 5, 'max' => 50]),
                new NotNull(),
                new Required()
            ]);

            if (0 !== count($violations)) {

                foreach ($violations as $violation)
                {
                    $validationErrors["fullname"] = $violation->getMessage();
                }

            }

            $violations = $validator->validate($email, [
                new Length(['min' => 6, 'max' => 50]),
                new Email(),
                new NotNull(),
                new Required()
            ]);

            if (0 !== count($violations)) {

                foreach ($violations as $violation)
                {
                    $validationErrors["email"] = $violation->getMessage();
                }

            }

            $violations = $validator->validate($phone, [
                new Length(['min' => 11, 'max' => 20]),
                new NotNull(),
                new Required()
            ]);

            if (0 !== count($violations)) {

                foreach ($violations as $violation)
                {
                    $validationErrors["phone"] = $violation->getMessage();
                }

            }

            $violations = $validator->validate($password, [
                new Length(['min' => 8, 'max' => 20]),
                new NotNull(),
                new Required()
            ]);

            if (0 !== count($violations)) {

                foreach ($violations as $violation)
                {
                    $validationErrors["password"] = $violation->getMessage();
                }

            }


            if ($validationErrors)
            {

                $result = [
                    "validationErrors" => $validationErrors
                ];

                echo json_encode($result);

                exit;
            }

            $userRepository = new UserRepository();
            $userRepository->username = $username;
            $userRepository->fullname = $fullname;
            $userRepository->email = $email;
            $userRepository->phone = $phone;
            $userRepository->password = $password;



            $result = $user->add($userRepository);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);
            }

            echo json_encode($result);
        }

    }

    public function login()
    {
        $user = new User();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $password = ($jsonData["password"]) ? $jsonData["password"] : null;

            $validationErrors = [];

            $validator = Validation::createValidator();
            $violations = $validator->validate($username, [
                new Length(['min' => 6,'max' => 50]),
                new NotNull(),
                new Required()
            ]);

            if (0 !== count($violations)) {

                foreach ($violations as $violation)
                {
                    $validationErrors["username"] = $violation->getMessage();
                }

            }

            $violations = $validator->validate($password, [
                new Length(['min' => 8, 'max' => 20]),
                new NotNull(),
                new Required()
            ]);

            if (0 !== count($violations)) {

                foreach ($violations as $violation)
                {
                    $validationErrors["password"] = $violation->getMessage();
                }

            }


            if ($validationErrors)
            {

                $result = [
                    "validationErrors" => $validationErrors
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

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);
            }

            echo json_encode($result);
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
    /*
               ?>
               <form action="" method="post" enctype="multipart/form-data">
                   <input type="file" name="dosya"/>
                   <input type="submit" value="Yükle">
               </form>
             <?php

               /*
               if($_FILES){
                   $filemanager = new FileManager();



                   $result = $filemanager->uploadFile($_FILES["dosya"],"News/".$_FILES["dosya"]["name"]);

                   echo $result;
               }
       */
    public function show(string $name): void
    {
        echo "SHOW";

        ?>
        <form action="add" method="post">
            <input type="text" name="adi"/>
            <input type="submit" value="gönder">
        </form>

        <?php
        /*
        echo "User:{$name}";

            $filemanager = new FileManager();
            $result = $filemanager->deleteFile("abc.jpg");

            echo $result;
        */
        /*
                $encoder = new Encoder();
                echo "Tuzsuz: ".$encoder->encode("Pass123");

                echo "<br><br>Tuzlu: ".$encoder->salt($encoder->encode("Pass123"))."<br><br>";

                $factory = new DatabaseFactory();

                var_dump($factory->db->delete("user",
                    ["id" => 4]));
                echo "<br><br><br>";

                $gUser = new UserWiper();

                var_dump($gUser->getRequests());

                var_dump($gUser->edit(6,1));

                $gUser->delete(1);
        */
    }
}