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
use App\Lib\Relations\ResourceRole;
use App\Lib\Relations\UserNews;
use App\Lib\Resource\Resource;
use App\Lib\Role\Role;
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

    public function showNews(string $url)
    {
        $News = new News();
        $Category = new Category();
        $News->url = $url;
        $NewsRepository = new NewsRepository();
        $CategoryRepository = new CategoryRepository();
        $CategoryNews = new CategoryNews();
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $Category->id = ($CategoryNews->getRelations(0,$Category,$News))["content"][0]["category_id"];

        $result = [
            "content" => [
                $NewsRepository->findNews($News),
                $CategoryRepository->findCategory($Category)
                ]
        ];

        echo json_encode($result);
    }

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

    public function getUserNewsList(int $page)
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        header('Content-Type: application/json; charset=utf-8', response_code: 406);

        if ($jsonData) {

            $username = ($jsonData["username"]) ? $jsonData["username"] : "";
            $url = ($jsonData["url"]) ? $jsonData["url"] : "";
            $title = ($jsonData["title"]) ? $jsonData["title"] : "";
            $token = ($jsonData["token"]) ? $jsonData["token"] : "";

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $News = new News();
            $UserNews = new UserNews();
            $NewsRepository = new NewsRepository();
            $User = new User();
            $UserRepository = new UserRepository();

            $News->url = $url;

            $User->username = $username;

            $getUser = $UserRepository->findUser($User);

            $Role = new Role();
            $Resource = new Resource();
            $Resource->resource_id = $getUser["id"];
            $Resource->resource_type = "user";

            $ResourceRole = new ResourceRole();
            $getAuthRole = $ResourceRole->getRole(0, $Resource, $Role);

            $result = ($getAuthRole->name == "Admin" || $getAuthRole->name == "Moderator") ? $NewsRepository->getNews($page, $News) : (($getAuthRole->name == "Editor") ? $UserNews->getUserNewsList($page, $User, $News) : []);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode($result);

                exit;

            }

            echo json_encode($this->errors);
        }

    }

    public function getUserCategoryNewsList(int $page)
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        header('Content-Type: application/json; charset=utf-8', response_code: 406);

        if ($jsonData)
        {
            $categoryUrl = ($jsonData["categoryUrl"]) ? $jsonData["categoryUrl"] : "";
            $username = ($jsonData["username"]) ? $jsonData["username"] : "";
            $url = ($jsonData["url"]) ? $jsonData["url"] : "";
            $title = ($jsonData["title"]) ? $jsonData["title"] : "";
            $token = ($jsonData["token"]) ? $jsonData["token"] : "";

            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $tokenO->token = $token;
            $tokenO->resource_type = "user";

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $News = new News();
            $UserNews = new UserNews();
            $NewsRepository = new NewsRepository();
            $Category = new Category();
            $CategoryNews = new CategoryNews();
            $User = new User();
            $UserRepository = new UserRepository();

            $News->url = $url;

            $User->username = $username;

            $getUser = $UserRepository->findUser($User);

            $Category->url = $categoryUrl;

            $Role = new Role();
            $Resource = new Resource();
            $Resource->resource_id = $getUser["id"];
            $Resource->resource_type = "user";

            $ResourceRole = new ResourceRole();
            $getAuthRole = $ResourceRole->getRole(0, $Resource, $Role);

            $result = ($getAuthRole->name == "Admin" || $getAuthRole->name == "Moderator") ? $CategoryNews->getCategoryNewsList($page, $Category, $News) : (($getAuthRole->name == "Editor") ? $UserNews->getUserCategoryNewsList($page, $User, $Category, $News) : []);

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode($result);

                exit;

            }

            echo json_encode($this->errors);
        }

    }

    public function getNewsComment()
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

    public function getNewsReadUser()
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

    public function getUserReadNews()
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

    public function readNews()
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
            $UserNews = new UserNews();
            $NewsRepository = new NewsRepository();
            $CategoryRepository = new CategoryRepository();
            $CategoryController = new CategoryController();
            $FileManager = new FileManager();
            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $User = new User();

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

            $User->username = $username;
            $result = $UserNews->add($User, $News);

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

            $categoryUrl = ($jsonData["categoryUrl"]) ? $jsonData["categoryUrl"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $id = ($jsonData["id"]) ? $jsonData["id"] : null;

            if ($id == null)
            {
                $result = [
                    "validationErrors" =>
                    [
                        "message" => "News is not found!"
                    ]
                ];
                echo json_encode($result);

                exit;
            }

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
            $UserNews = new UserNews();
            $NewsRepository = new NewsRepository();
            $CategoryRepository = new CategoryRepository();
            $CategoryController = new CategoryController();
            $FileManager = new FileManager();
            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $User = new User();

            $tokenO->token = $token;

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $News->id = $id;

            $getNews = $NewsRepository->findNews($News);

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
            
            if ($img)
            {
            $base64File = new Base64File($img);
            $filename = $News->url.".".$base64File->getExtension();
            $News->img = $filename;
            }
            else
            {
                $News->img = $getNews["img"];
            }

            $result = $NewsRepository->edit($News);

            if (!$result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 406);

                $this->validationErrors["message"] = "The latest version of the news could not be saved";
                $Validation->ValidationErrorControl($this->validationErrors);

                exit;
            }

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                echo json_encode([
                    "message" => "News Updated"
                ]);

                if ($img != "")
                {
                    $lastImg = $getNews["img"];
                    $FileManager->deleteFile("News/$lastImg");
                    $result = $FileManager->putContentFile($filename, "News/", $base64File);
                }
                exit;
            }

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

            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $id = ($jsonData["id"]) ? $jsonData["id"] : null;

            if ($id == null)
            {
                $this->validationErrors["message"] = "News is not found!";
                $Validation->ValidationErrorControl($this->validationErrors);

                exit;
            }

            $token = ($jsonData["token"]) ? $jsonData["token"] : null;


            $Category = new Category();
            $News = new News();
            $CharCorrector = new Corrector();
            $CategoryNews = new CategoryNews();
            $UserNews = new UserNews();
            $NewsRepository = new NewsRepository();
            $CategoryRepository = new CategoryRepository();
            $CategoryController = new CategoryController();
            $FileManager = new FileManager();
            $tokenO = new Token();
            $tokenRepository = new TokenRepository();
            $User = new User();

            $tokenO->token = $token;

            $tokenRepository->tokenControl($tokenO, $this->errors);

            $News->id = $id;
            $User->username = $username;

            $getNews = $NewsRepository->findNews($News);
            $result = $NewsRepository->delete($News);

            if (!$result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 406);

                $this->validationErrors["message"] = "News is not deleted";
                $Validation->ValidationErrorControl($this->validationErrors);

                exit;
            }

            if ($result)
            {
                header('Content-Type: application/json; charset=utf-8', response_code: 201);

                $CategoryNews->delete($Category,$News);
                $UserNews->delete($User,$News);

                echo json_encode([
                    "message" => "News Deleted"
                ]);

                    $lastImg = $getNews["img"];
                    $FileManager->deleteFile("News/$lastImg");

                exit;
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