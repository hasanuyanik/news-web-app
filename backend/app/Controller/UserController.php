<?php

namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Auth\UserAuthService;
use App\Lib\Logger\Logger;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;
use App\Lib\User\UserWiper;
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
            "phone" => "Unauthorized"
        ]
    ];

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

            $this->UsernameValidation($username);
            $this->FullnameValidation($fullname);
            $this->EmailValidation($email);
            $this->PhoneValidation($phone);
            $this->PasswordValidation($password);

            if ($this->validationErrors)
            {
                $result = [
                    "validationErrors" => $this->validationErrors
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

    public function edit()
    {

        $user = new User();

        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
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

                echo json_encode($errors);

                exit;
            }

            $this->FullnameValidation($fullname);
            $this->EmailValidation($email);
            $this->PhoneValidation($phone);

            if ($this->validationErrors)
            {
                $result = [
                    "validationErrors" => $this->validationErrors
                ];

                echo json_encode($result);

                exit;
            }

            $userRepository->username = $username;
            $userRepository->fullname = $fullname;
            $userRepository->email = $email;
            $userRepository->phone = $phone;
            $userRepository->password = $resultForId[0]["password"];

            $result = $user->edit($userRepository);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                $editResultRepo = new UserRepository();
                $editResultRepo->username = $username;
                $result = $user->getUsers($editResultRepo);

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
        $userRepository->username = $name;
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = $user->getUsers($userRepository);

        $result[0]["id"] = "";

        echo json_encode($result);
    }

    public function index(): void
    {
        $message = "Error : {errorlevel} | Message : {errormessage} | File : {errorfile} | line : {errorline} ";

        $context = [
            "errorlevel" => "ErrorLevel1",
            "errormessage" => "ErrorMessage1",
            "errorfile" => "ErrorFile1",
            "errorline" => "ErrorLine1"
        ];

        $logger = new Logger();
        $logger->error($message, $context);
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