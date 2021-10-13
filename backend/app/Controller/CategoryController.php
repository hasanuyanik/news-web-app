<?php
namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\News\NewsRepository;
use App\Lib\Relations\Category_News;
use App\Lib\Relations\Category_User;
use App\Lib\Relations\Follow_Category;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;

class CategoryController extends BaseController
{
    public array $validationErrors = [];
    public array $errors = [
        "validationErrors" => [
            "category_name" => "Unauthorized"
        ]
    ];

    public function getCategories(int $page)
    {
        $category = new Category();
        $categoryRepository = new CategoryRepository();
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = [
            "content" => $category->getCategories($page, $categoryRepository)
        ];

        echo json_encode($result);
    }

    public function getCategories_News()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $title = ($jsonData["title"]) ? $jsonData["title"] : null;
            $description = ($jsonData["description"]) ? $jsonData["description"] : null;
            $content = ($jsonData["content"]) ? $jsonData["content"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category_news = new Category_News();
            $categoryRepository = new CategoryRepository();
            $newsRepository = new NewsRepository();
            $NewsController = new NewsController();

            $categoryRepository->name = $category_name;
            $newsRepository->title = $title;
            $newsRepository->description = $description;
            $newsRepository->content = $content;

            $NewsController->TitleValidation($title);
            $NewsController->DescriptionValidation($description);
            $NewsController->ContentValidation($content);

            if ($NewsController->validationErrors)
            {
                $result = [
                    "validationErrors" => $NewsController->validationErrors
                ];
                echo json_encode($result);
                exit;
            }

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $UserController = new UserController();

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $category_news->getCategory_NewsList($page, $categoryRepository, $newsRepository)
            ];

            echo json_encode($result);
        }
    }

    public function getCategories_FollowingUser()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $follow = new Follow_Category();
            $categoryRepository = new CategoryRepository();
            $userRepository = new UserRepository();

            $categoryRepository->name = $category_name;
            $userRepository->username = $username;

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $UserController = new UserController();

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $follow->getCategory_UserList($page, $categoryRepository, $userRepository)
            ];

            echo json_encode($result);
        }
    }

    public function getUser_FollowedCategories()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $follow = new Follow_Category();
            $categoryRepository = new CategoryRepository();
            $userRepository = new UserRepository();

            $categoryRepository->name = $category_name;
            $userRepository->username = $username;

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $UserController = new UserController();

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $follow->getCategory_UserList($page, $categoryRepository, $userRepository)
            ];

            echo json_encode($result);
        }
    }

    public function userAssign_Category()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $process = ($jsonData["process"]) ? $jsonData["process"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category_user = new Category_User();
            $category = new Category();
            $user = new User();
            $categoryRepository = new CategoryRepository();
            $userRepository = new UserRepository();
            $UserController = new UserController();

            $categoryRepository->name = $category_name;
            $userRepository->username = $username;

            $UserController->UsernameValidation($username);

            if ($UserController->validationErrors)
            {
                $result = [
                    "validationErrors" => $UserController->validationErrors
                ];
                echo json_encode($result);
                exit;
            }

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $UserController = new UserController();

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $categoryRepository->id = ($category->getCategories(0,$categoryRepository))[0]["id"];
            $userRepository->id = ($user->getUsers($userRepository,0))[0]["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            if ($process == 1)
            {
                $result = $category_user->add($categoryRepository, $userRepository);
            }
            else
            {
                $result = $category_user->delete($categoryRepository, $userRepository);
            }
            echo json_encode($result);
        }
    }

    public function follow_Category()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $process = ($jsonData["process"]) ? $jsonData["process"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category_user = new Category_User();
            $category = new Category();
            $user = new User();
            $follow = new Follow_Category();
            $categoryRepository = new CategoryRepository();
            $userRepository = new UserRepository();
            $UserController = new UserController();

            $categoryRepository->name = $category_name;
            $userRepository->username = $username;

            $UserController->UsernameValidation($username);

            if ($UserController->validationErrors)
            {
                $result = [
                    "validationErrors" => $UserController->validationErrors
                ];
                echo json_encode($result);
                exit;
            }

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;
            $UserController = new UserController();

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            $categoryRepository->id = ($category->getCategories(0,$categoryRepository))[0]["id"];
            $userRepository->id = ($user->getUsers($userRepository,0))[0]["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            if ($process == 1)
            {
                $result = $follow->add($categoryRepository, $userRepository);
            }
            else
            {
                $result = $follow->delete($categoryRepository, $userRepository);
            }
            echo json_encode($result);
        }
    }

    public function add()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category = new Category();
            $categoryRepository = new CategoryRepository();

            $categoryRepository->name = $category_name;

            $this->CategoryNameValidation($category_name);

            if ($this->validationErrors)
            {
                $result = [
                    "validationErrors" => $this->validationErrors
                ];
                echo json_encode($result);
                exit;
            }

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($this->errors);

                exit;
            }

            $categoryRepository->id = ($category->getCategories(0,$categoryRepository))[0]["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $category->add($categoryRepository);

            echo json_encode($result);
        }
    }

    public function edit()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category = new Category();
            $categoryRepository = new CategoryRepository();

            $categoryRepository->name = $category_name;

            $this->CategoryNameValidation($category_name);

            if ($this->validationErrors)
            {
                $result = [
                    "validationErrors" => $this->validationErrors
                ];
                echo json_encode($result);
                exit;
            }

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($this->errors);

                exit;
            }

            $categoryRepository->id = ($category->getCategories(0,$categoryRepository))[0]["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $category->edit($categoryRepository);

            echo json_encode($result);
        }
    }

    public function delete()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $category_name = ($jsonData["category_name"]) ? $jsonData["category_name"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category = new Category();
            $categoryRepository = new CategoryRepository();

            $categoryRepository->name = $category_name;

            $this->CategoryNameValidation($category_name);

            if ($this->validationErrors)
            {
                $result = [
                    "validationErrors" => $this->validationErrors
                ];
                echo json_encode($result);
                exit;
            }

            $tokenControl = new Token();
            $tokenRepository = new TokenRepository();
            $tokenRepository->token = $token;

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($this->errors);

                exit;
            }

            $categoryRepository->id = ($category->getCategories(0,$categoryRepository))[0]["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $category->delete($categoryRepository);

            echo json_encode($result);
        }
    }

    public function CategoryNameValidation($category_name)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($category_name, [
            new Length(['min' => 4,'max' => 50]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["category_name"] = $violation->getMessage();
            }

        }
    }
}