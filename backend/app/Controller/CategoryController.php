<?php
namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\Corrector;
use App\Lib\News\News;
use App\Lib\Relations\CategoryNews;
use App\Lib\Relations\CategoryUser;
use App\Lib\Relations\FollowCategory;
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
            "name" => "Unauthorized",
            "url" => "Unauthorized"
        ]
    ];

    public function getCategories(int $page)
    {
        $Category = new Category();
        $CategoryRepository = new CategoryRepository();
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = $CategoryRepository->getCategories($page, $Category);

        echo json_encode($result);
    }

    public function getCategoryUserList(string $categoryUrl, int $page)
    {
        $Category = new Category();
        $User = new User();
        $CategoryUser = new CategoryUser();

        $Category->url = $categoryUrl;

        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = $CategoryUser->getCategoryUserList($page, $Category, $User);

        echo json_encode($result);
    }

    public function getCategoryUserRelation(string $categoryUrl, int $page)
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $categoryName = ($jsonData["name"]) ? $jsonData["name"] : null;
            $categoryUrl = ($jsonData["url"]) ? $jsonData["url"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $authUser = ($jsonData["authUser"]) ? $jsonData["authUser"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $CategoryUser = new CategoryUser();
            $Category = new Category();
            $User = new User();
            $UserController = new UserController();

            $Category->name = $categoryName;
            $Category->url = $categoryUrl;
            $User->username = $username;

            $UserController->UsernameValidation($username);

            $Validation->ValidationErrorControl($UserController->validationErrors);

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $CategoryUser->getRelations($page, $Category, $User)
            ];

            echo json_encode($result);
        }
    }

    public function show(string $categoryUrl): void
    {
        $Category = new Category();
        $CategoryRepository = new CategoryRepository();
        $Category->url = $categoryUrl;

        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = $CategoryRepository->findCategory($Category);

        echo json_encode($result);
    }

    public function getCategoriesNews()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $categoryName = ($jsonData["categoryName"]) ? $jsonData["categoryName"] : null;
            $title = ($jsonData["title"]) ? $jsonData["title"] : null;
            $description = ($jsonData["description"]) ? $jsonData["description"] : null;
            $content = ($jsonData["content"]) ? $jsonData["content"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $CategoryNews = new CategoryNews();
            $Category = new Category();
            $News = new News();
            $NewsController = new NewsController();

            $Category->name = $categoryName;
            $News->title = $title;
            $News->description = $description;
            $News->content = $content;

            $NewsController->TitleValidation($title);
            $NewsController->DescriptionValidation($description);
            $NewsController->ContentValidation($content);

            $Validation->ValidationErrorControl($NewsController->validationErrors);

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $UserController = new UserController();

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $CategoryNews->getCategoryNewsList($page, $Category, $News)
            ];

            echo json_encode($result);
        }
    }

    public function getCategoriesFollowingUser()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $categoryName = ($jsonData["categoryName"]) ? $jsonData["categoryName"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $FollowCategory = new FollowCategory();
            $Category = new Category();
            $User = new User();

            $Category->name = $categoryName;
            $User->username = $username;

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $UserController = new UserController();

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $FollowCategory->getCategoryUserList($page, $Category, $User)
            ];

            echo json_encode($result);
        }
    }

    public function getUserFollowedCategories()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $categoryName = ($jsonData["categoryName"]) ? $jsonData["categoryName"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $FollowCategory = new FollowCategory();
            $Category = new Category();
            $User = new User();

            $Category->name = $categoryName;
            $User->username = $username;

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $UserController = new UserController();

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $FollowCategory->getCategoryUserList($page, $Category, $User)
            ];

            echo json_encode($result);
        }
    }

    public function userAssignCategory()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $categoryName = ($jsonData["categoryName"]) ? $jsonData["categoryName"] : null;
            $process = ($jsonData["process"]) ? $jsonData["process"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $CategoryUser = new CategoryUser();
            $Category = new Category();
            $User = new User();
            $CategoryRepository = new CategoryRepository();
            $UserRepository = new UserRepository();
            $UserController = new UserController();

            $Category->name = $categoryName;
            $User->username = $username;

            $UserController->UsernameValidation($username);

            $Validation->ValidationErrorControl($UserController->validationErrors);

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $UserController = new UserController();

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            $Category->id = ($CategoryRepository->findCategory($Category))["id"];
            $User->id = ($UserRepository->findUser($User))["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            if ($process == 1)
            {
                $result = $CategoryUser->add($Category, $User);
            }
            else
            {
                $result = $CategoryUser->delete($Category, $User);
            }
            echo json_encode($result);
        }
    }

    public function followCategory()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $categoryName = ($jsonData["categoryName"]) ? $jsonData["categoryName"] : null;
            $process = ($jsonData["process"]) ? $jsonData["process"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $CategoryUser = new CategoryUser();
            $Category = new Category();
            $User = new User();
            $FollowCategory = new FollowCategory();
            $CategoryRepository = new CategoryRepository();
            $UserRepository = new UserRepository();
            $UserController = new UserController();

            $Category->name = $categoryName;
            $User->username = $username;

            $UserController->UsernameValidation($username);

            $Validation->ValidationErrorControl($UserController->validationErrors);

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $UserController = new UserController();

            $tokenRepository->tokenControl($tokenO, $UserController->errors);

            $Category->id = ($CategoryRepository->findCategory($Category))["id"];
            $User->id = ($UserRepository->findUser($User))["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            if ($process == 1)
            {
                $result = $FollowCategory->add($Category, $User);
            }
            else
            {
                $result = $FollowCategory->delete($Category, $User);
            }
            echo json_encode($result);
        }
    }

    public function add()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $categoryName = ($jsonData["name"]) ? $jsonData["name"] : null;
            $categoryUrl = ($jsonData["url"]) ? $jsonData["url"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $Category = new Category();
            $CategoryRepository = new CategoryRepository();
            $CharCorrector = new Corrector();

            $Category->name = $categoryName;
            $Category->url = $CharCorrector->charCorrectorSentenceToUrl($categoryUrl);

            $this->CategoryNameValidation($categoryName);
            $this->CategoryUrlValidation($Category->url);

            $Validation->ValidationErrorControl($this->validationErrors);

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $Category->id = ($CategoryRepository->findCategory($Category))["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $CategoryRepository->add($Category);

            echo json_encode($result);
        }
    }

    public function edit()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $categoryId = ($jsonData["id"]) ? $jsonData["id"] : null;
            $categoryName = ($jsonData["name"]) ? $jsonData["name"] : null;
            $categoryUrl = ($jsonData["url"]) ? $jsonData["url"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $Category = new Category();
            $CategoryRepository = new CategoryRepository();
            $CharCorrector = new Corrector();

            $Category->id = $categoryId;
            $Category->name = $categoryName;
            $Category->url = $CharCorrector->charCorrectorSentenceToUrl($categoryUrl);

            $this->CategoryNameValidation($Category->name);
            $this->CategoryUrlValidation($Category->url);

            $CategoryForUnique = new Category();
            $CategoryForUnique->name = $Category->name;

            $uniqueNameControl = $CategoryRepository->findCategory($CategoryForUnique);

            if ($uniqueNameControl["id"] != $Category->id)
            {
                $this->validationErrors["name"] = ($uniqueNameControl["name"] == $Category->name) ? "Category Name is Unique!" : $this->validationErrors["name"];
            }

            $CategoryForUnique->name = "";
            $CategoryForUnique->url = $Category->url;

            $uniqueUrlControl = $CategoryRepository->findCategory($CategoryForUnique);

            if ($uniqueUrlControl["id"] != $Category->id)
            {
                $this->validationErrors["url"] = ($uniqueUrlControl["url"] == $Category->url) ? "Category Url is Unique!" : $this->validationErrors["url"];
            }

            $Validation->ValidationErrorControl($this->validationErrors);

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;

            $tokenRepository->tokenControl($tokenO, $this->errors);

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $CategoryRepository->edit($Category);

            echo json_encode($result);
        }
    }

    public function delete()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            $Validation = new \App\Lib\Validation();

            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $categoryName = ($jsonData["name"]) ? $jsonData["name"] : null;
            $categoryUrl = ($jsonData["url"]) ? $jsonData["url"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $Category = new Category();
            $CategoryRepository = new CategoryRepository();

            $Category->name = $categoryName;

            $this->CategoryNameValidation($categoryName);

            $Validation->ValidationErrorControl($this->validationErrors);

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $Category->id = ($CategoryRepository->findCategory($Category))["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $CategoryRepository->delete($Category);

            echo json_encode($result);
        }
    }

    public function CategoryNameValidation($categoryName)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($categoryName, [
            new Length(['min' => 4,'max' => 50]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["name"] = $violation->getMessage();
            }

        }
    }

    public function CategoryUrlValidation($categoryUrl)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($categoryUrl, [
            new Length(['min' => 3,'max' => 150]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["url"] = $violation->getMessage();
            }

        }
    }
}