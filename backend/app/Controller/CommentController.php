<?php
namespace App\Controller;

use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\Comment\Comment;
use App\Lib\Comment\CommentRepository;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;
use App\Lib\Relations\Category_News;
use App\Lib\Relations\News_Comment;
use App\Lib\Relations\User_Comment;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;

class CommentController extends BaseController
{
    public array $validationErrors = [];
    public array $errors = [
        "validationErrors" => [
            "name" => "Unauthorized",
            "comment" => "Unauthorized"
        ]
    ];

    public function getComments(int $page)
    {
        $comment = new Comment();
        $commentRepository = new CommentRepository();
        header('Content-Type: application/json; charset=utf-8',response_code: 201);

        $result = [
            "content" => $comment->getComments($page, $commentRepository)
        ];

        echo json_encode($result);
    }

    public function getUser_Comment()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $page = ($jsonData["page"]) ? $jsonData["page"] : null;

            $comment_name = ($jsonData["comment_name"]) ? $jsonData["comment_name"] : null;
            $comment = ($jsonData["comment"]) ? $jsonData["comment"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;

            $user_comment = new User_Comment();
            $userRepository = new UserRepository();
            $commentRepository = new CommentRepository();
            $UserController = new UserController();

            $commentRepository->name = $comment_name;
            $commentRepository->comment = $comment;
            $userRepository->username = $username;

            $UserController->UsernameValidation($username);

            $this->NameValidation($comment_name);
            $this->CommentValidation($comment);

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

                echo json_encode($UserController->errors);

                exit;
            }

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = [
                "content" => $user_comment->getUser_CommentList($page, $userRepository, $commentRepository)
            ];

            echo json_encode($result);
        }
    }

    public function add()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $comment_name = ($jsonData["comment_name"]) ? $jsonData["comment_name"] : null;
            $post_comment = ($jsonData["comment"]) ? $jsonData["comment"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;


            $news = new News();
            $news_comment = new News_Comment();
            $user_comment = new User_Comment();
            $comment = new Comment();
            $userRepository = new UserRepository();
            $newsRepository = new NewsRepository();
            $commentRepository = new CommentRepository();

            $userRepository->username = $username;
            $newsRepository->id = $news_id;
            $commentRepository->name = $comment_name;
            $commentRepository->comment = $post_comment;

            $this->NameValidation($comment_name);
            $this->CommentValidation($post_comment);

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

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $comment->add($commentRepository);

            $commentRepository->id = ($comment->getComments(0,$commentRepository))[0]["id"];

            $user_comment->add($userRepository, $commentRepository);

            $result = $news_comment->add($newsRepository, $commentRepository);

            echo json_encode($result);
        }
    }

    public function edit()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $comment_id = ($jsonData["comment_id"]) ? $jsonData["comment_id"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $comment_name = ($jsonData["comment_name"]) ? $jsonData["comment_name"] : null;
            $post_comment = ($jsonData["comment"]) ? $jsonData["comment"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;


            $news = new News();
            $news_comment = new News_Comment();
            $user_comment = new User_Comment();
            $comment = new Comment();
            $userRepository = new UserRepository();
            $newsRepository = new NewsRepository();
            $commentRepository = new CommentRepository();

            $userRepository->username = $username;
            $newsRepository->id = $news_id;
            $commentRepository->id = $comment_id;
            $commentRepository->name = $comment_name;
            $commentRepository->comment = $post_comment;

            $this->NameValidation($comment_name);
            $this->CommentValidation($post_comment);

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

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $comment->edit($commentRepository);

            echo json_encode($result);
        }
    }

    public function delete()
    {
        $posts = file_get_contents('php://input');
        $jsonData = json_decode($posts, true);

        if ($jsonData) {
            header('Content-Type: application/json; charset=utf-8', response_code: 406);

            $news_id = ($jsonData["news_id"]) ? $jsonData["news_id"] : null;
            $comment_id = ($jsonData["comment_id"]) ? $jsonData["comment_id"] : null;
            $username = ($jsonData["username"]) ? $jsonData["username"] : null;
            $comment_name = ($jsonData["comment_name"]) ? $jsonData["comment_name"] : null;
            $post_comment = ($jsonData["comment"]) ? $jsonData["comment"] : null;
            $token = ($jsonData["token"]) ? $jsonData["token"] : null;


            $news = new News();
            $news_comment = new News_Comment();
            $user_comment = new User_Comment();
            $comment = new Comment();
            $userRepository = new UserRepository();
            $newsRepository = new NewsRepository();
            $commentRepository = new CommentRepository();

            $userRepository->username = $username;
            $newsRepository->id = $news_id;
            $commentRepository->id = $comment_id;
            $commentRepository->name = $comment_name;
            $commentRepository->comment = $post_comment;

            $this->NameValidation($comment_name);
            $this->CommentValidation($post_comment);

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

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            $result = $comment->delete($commentRepository);

            echo json_encode($result);
        }
    }

    public function NameValidation($name)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($name, [
            new Length(['min' => 5,'max' => 50]),
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

    public function CommentValidation($comment)
    {

        $validator = Validation::createValidator();
        $violations = $validator->validate($comment, [
            new Length(['min' => 20,'max' => 200]),
            new NotNull(),
            new Required()
        ]);

        if (0 !== count($violations)) {

            foreach ($violations as $violation)
            {
                $this->validationErrors["comment"] = $violation->getMessage();
            }

        }
    }

}