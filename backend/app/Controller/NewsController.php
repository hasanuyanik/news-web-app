<?php
namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\Comment\CommentRepository;
use App\Lib\Corrector;
use App\Lib\FileManager\FileManager;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;
use App\Lib\Relations\CategoryNews;
use App\Lib\Relations\NewsComment;
use App\Lib\Relations\ReadNews;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use BitAndBlack\Base64String\Base64File;
use Jsnlib\UploadImageBase64;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;

class NewsController extends BaseController
{
    public array $validationErrors = [];
    public array $errors = [
        "validationErrors" => [
            "title" => "Unauthorized",
            "url" => "Unauthorized",
            "description" => "Unauthorized",
            "content" => "Unauthorized",
            "img" => "Unauthorized",
            "message" => ""
        ]
    ];

    public function getNews(int $page)
    {
        $news = new News();
        $newsRepository = new NewsRepository();
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = [
            "content" => $news->getNews($page, $newsRepository)
        ];

        echo json_encode($result);
    }

    public function getNews_Comment()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $comment_name = ($jsonData["comment_name"]) ? $jsonData["comment_name"] : null;
            $title = ($jsonData["title"]) ? $jsonData["title"] : null;
            $description = ($jsonData["description"]) ? $jsonData["description"] : null;
            $content = ($jsonData["content"]) ? $jsonData["content"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $news_comment = new News_Comment();
            $newsRepository = new NewsRepository();
            $commentRepository = new CommentRepository();

            $commentRepository->name = $comment_name;
            $newsRepository->title = $title;
            $newsRepository->description = $description;
            $newsRepository->content = $content;

            $this->TitleValidation($title);
            $this->DescriptionValidation($description);
            $this->ContentValidation($content);

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
            $UserController = new UserController();

            if ($tokenControl->tokenControl($tokenRepository) == false)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 401);

                echo json_encode($UserController->errors);

                exit;
            }

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $news_comment->getNews_CommentList($page, $newsRepository, $commentRepository)
            ];

            echo json_encode($result);
        }
    }

    public function getNews_ReadUser()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $readNews = new Read_News();
            $newsRepository = new NewsRepository();
            $userRepository = new UserRepository();

            $newsRepository->id = $news_id;
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
                "content" => $readNews->getNews_UserList($page, $newsRepository, $userRepository)
            ];

            echo json_encode($result);
        }
    }

    public function getUser_ReadNews()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $readNews = new Read_News();
            $newsRepository = new NewsRepository();
            $userRepository = new UserRepository();

            $newsRepository->id = $news_id;
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
                "content" => $readNews->getUser_NewsList($page, $newsRepository, $userRepository)
            ];

            echo json_encode($result);
        }
    }

    public function read_News()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $process = ($jsonData["process"]) ? $jsonData["process"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $readNews = new Read_News();
            $user = new User();
            $newsRepository = new NewsRepository();
            $userRepository = new UserRepository();
            $UserController = new UserController();

            $newsRepository->id = $news_id;
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

            $userRepository->id = ($user->getUsers($userRepository,0))[0]["id"];

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            if ($process == 1)
            {
                $result = $readNews->add($newsRepository, $userRepository);
            }
            else
            {
                $result = $readNews->delete($newsRepository, $userRepository);
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

            $categoryUrl = ($jsonData["categoryUrl"]) ? $jsonData["categoryUrl"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $title = ($jsonData["title"]) ? $jsonData["title"] : null;
            $url = ($jsonData["url"]) ? $jsonData["url"] : null;
            $description = ($jsonData["description"]) ? $jsonData["description"] : null;
            $content = ($jsonData["content"]) ? $jsonData["content"] : null;
            $img = ($jsonData["image"]) ? $jsonData["image"] : "";
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $Validation = new \App\Lib\Validation();
            $Category = new Category();
            $News = new News();
            $CharCorrector = new Corrector();
            $CategoryNews = new CategoryNews();
            $NewsRepository = new NewsRepository();
            $CategoryRepository = new CategoryRepository();
            $CategoryController = new CategoryController();
            $FileManager = new FileManager();
            $tokenO = new Token();
            $tokenRepository = new TokenRepository();

            $tokenO->token = $token;

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $CategoryController->CategoryUrlValidation($categoryUrl);
            $Validation->ValidationErrorControl($CategoryController->validationErrors);

            $News->title = $title;
            $News->url = $CharCorrector->charCorrectorSentenceToUrl($url);
            $News->description = $description;
            $News->content = $content;


            $this->TitleValidation($title);
            $this->UrlValidation($News->url);
            $this->DescriptionValidation($description);
            $this->ContentValidation($content);
            $this->ImgValidation($img);

            $Validation->ValidationErrorControl($this->validationErrors);

            $base64File = new Base64File($img);

            $filename = $News->url.".".$base64File->getExtension();
            $News->img = $filename;

            $Category->url = $categoryUrl;
            $Category->id = ($CategoryRepository->findCategory($Category))["id"];

            $result = $NewsRepository->add($News);

            if (!$result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 406);

                $this->validationErrors["message"] = "News available. Try something different";
                $Validation->ValidationErrorControl($this->validationErrors);

                exit;
            }

            $News->id = ($NewsRepository->findNews($News))["id"];

            $result = $CategoryNews->add($Category, $News);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode($result);

                $result = $FileManager->putContentFile($filename,"News/",$base64File);

                exit;
            }

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
            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $title = ($jsonData["title"]) ? $jsonData["title"] : null;
            $description = ($jsonData["description"]) ? $jsonData["description"] : null;
            $content = ($jsonData["content"]) ? $jsonData["content"] : null;
            $img = ($jsonData["img"]) ? $jsonData["img"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category = new Category();
            $news = new News();
            $category_news = new CategoryNews();
            $newsRepository = new NewsRepository();
            $categoryRepository = new CategoryRepository();

            $categoryRepository->name = $category_name;

            $this->TitleValidation($title);
            $this->DescriptionValidation($description);
            $this->ContentValidation($content);

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

            $newsRepository->id = $news_id;
            $newsRepository->title = $title;
            $newsRepository->description = $description;
            $newsRepository->content = $content;
            $newsRepository->img = $img;

            $result = $news->edit($newsRepository);

            $relationControlForChangeCategory = $category_news->getRelations(0, $categoryRepository, $newsRepository);
            if (!$relationControlForChangeCategory)
            {
                $category_news->delete(new CategoryRepository(), $newsRepository);
                $category_news->add($categoryRepository, $newsRepository);
            }

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
            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $title = ($jsonData["title"]) ? $jsonData["title"] : null;
            $description = ($jsonData["description"]) ? $jsonData["description"] : null;
            $content = ($jsonData["content"]) ? $jsonData["content"] : null;
            $img = ($jsonData["img"]) ? $jsonData["img"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $category = new Category();
            $news = new News();
            $category_news = new Category_News();
            $newsRepository = new NewsRepository();
            $categoryRepository = new CategoryRepository();

            $categoryRepository->name = $category_name;

            $this->TitleValidation($title);
            $this->DescriptionValidation($description);
            $this->ContentValidation($content);

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

            $newsRepository->id = $news_id;
            $newsRepository->title = $title;
            $newsRepository->description = $description;
            $newsRepository->content = $content;
            $newsRepository->img = $img;

            $result = $news->delete($newsRepository);

            $relationControlForChangeCategory = $category_news->getRelations(0, $categoryRepository, $newsRepository);
            if ($relationControlForChangeCategory)
            {
                $category_news->delete($categoryRepository, $newsRepository);
            }

            echo json_encode($result);
        }
    }

    public function TitleValidation($title)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($title, [
            new Length(['min' => 6,'max' => 50]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["title"] = $violation->getMessage();
            }

        }
    }

    public function UrlValidation($url)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($url, [
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

    public function DescriptionValidation($description)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($description, [
            new Length(['min' => 20,'max' => 200]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["description"] = $violation->getMessage();
            }

        }
    }

    public function ContentValidation($content)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($content, [
            new Length(['min' => 100,'max' => 600]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["content"] = $violation->getMessage();
            }

        }
    }

    public function ImgValidation($img)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($img, [
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["img"] = $violation->getMessage();
            }

        }
    }
}