<?php

namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Logger\Logger;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;

class UserController extends BaseController
{
    public array $validationErrors = [];
    public array $errors = [
        "validationErrors" => [
            "fullname" => "Unauthorized",
            "email" => "Unauthorized",
            "phone" => "Unauthorized",
            "message" => "Unauthorized"
        ]
    ];

    public function getUsers(int $page)
    {
        $user = new User();
        $userRepository = new UserRepository();

        header('Content-Type: application/json; charset=utf-8',response_code: 201);
        $result = [
            "content" => $userRepository->getUsers($user, $page)
        ];

        echo json_encode($result);

    }

    public function add()
    {
        $Validation = new \App\Lib\Validation();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $fullname = ($jsonData["fullname"]) ? $jsonData["fullname"] : null;
            $email = ($jsonData["email"]) ? $jsonData["email"] : null;
            $phone = ($jsonData["phone"]) ? $jsonData["phone"] : null;
            $password = ($jsonData["password"]) ? $jsonData["password"] : null;

            $this->UsernameValidation($username);
            $this->FullnameValidation($fullname);
            $this->EmailValidation($email);
            $this->PhoneValidation($phone);
            $this->PasswordValidation($password);

            $user = new User();
            $userRepository = new UserRepository();

            if ($userRepository->UniqueUsername($username))
            {
                $this->validationErrors["username"] = "Username Not Unique";
            }

            $Validation->ValidationErrorControl($this->validationErrors);



            $user->username = $username;
            $user->fullname = $fullname;
            $user->email = $email;
            $user->phone = $phone;
            $user->password = $password;

            $result = $userRepository->add($user);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);
            }

            echo json_encode($result);
        }

    }

    public function edit()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            $user = new User();
            $userRepository = new UserRepository();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $fullname = ($jsonData["fullname"]) ? $jsonData["fullname"] : null;
            $email = ($jsonData["email"]) ? $jsonData["email"] : null;
            $phone = ($jsonData["phone"]) ? $jsonData["phone"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            if ($token == null)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($this->errors);

                exit;
            }

            $user->username = $username;
            $resultForId = $userRepository->getUsers($user);

            $user->id = $resultForId[0]["id"];

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_id = $user->id;
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $this->FullnameValidation($fullname);
            $this->EmailValidation($email);
            $this->PhoneValidation($phone);

            $Validation->ValidationErrorControl($this->validationErrors);

            $user->username = $username;
            $user->fullname = $fullname;
            $user->email = $email;
            $user->phone = $phone;
            $user->password = $resultForId[0]["password"];

            $result = $userRepository->edit($user);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                $currentUser = new User();
                $currentUser->username = $username;
                $result = $userRepository->getUsers($currentUser);

                $result[0]["id"] = "";

                echo json_encode($result);

                exit;
            }

            echo json_encode($result);
        }

    }

    public function show(string $name): void
    {
        $user = new User();
        $userRepository = new UserRepository();
        $user->username = $name;
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = $userRepository->getUsers($user);

        $result[0]["id"] = "";

        echo json_encode($result);
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
    /*
        echo "SHOW";

        ?>
        <form action="add" method="post">
            <input type="text" name="adi"/>
            <input type="submit" value="gönder">
        </form>

        <?php

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


    public function UsernameValidation($username)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($username, [
            new Length(['min' => 6,'max' => 50]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["username"] = $violation->getMessage();
            }

        }
    }

    public function FullnameValidation($fullname)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($fullname, [
            new Length(['min' => 5, 'max' => 50]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["fullname"] = $violation->getMessage();
            }

        }
    }

    public function EmailValidation($email)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($email, [
            new Length(['min' => 6, 'max' => 50]),
            new Email(),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["email"] = $violation->getMessage();
            }

        }
    }

    public function PhoneValidation($phone)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($phone, [
            new Length(['min' => 11, 'max' => 20]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["phone"] = $violation->getMessage();
            }

        }
    }

    public function PasswordValidation($password)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($password, [
            new Length(['min' => 8, 'max' => 20]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["password"] = $violation->getMessage();
            }

        }
    }
}