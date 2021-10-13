<?php
namespace App\Lib\Relations;

use App\Lib\News\News;
use App\Lib\News\NewsRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\User\User;
use App\Lib\User\UserRepository;

class Read_News
{
    public function getNews_UserList(int $page, ?NewsRepository $news, ?UserRepository $user): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($news->id == null) ? [] : ["category_id"=>$news->id];

        $getNews = (new News())->getNews(0,$news);

        if ($news->id == null)
        {
            $fields = ($getNews[0]["id"]) ? ["news_id"=>$getNews[0]["id"]] : [];
        }

        $likeFields = [];

        $news_user = $db->findAll("read_news",$fields,$page, $likeFields);

        $news_userList = [];

        foreach ($news_user as $relation)
        {
            $UserRepository = new UserRepository();
            $UserRepository->id = $relation["user_id"];
            $getUser = (new User())->getUsers($UserRepository, 0);

            $getUser[0]["id"] = "";
            $getUser[0]["password"] = "";
            array_push($news_userList, $getUser[0]);
        }

        $result = [
            "news" => $getNews[0],
            "userList" => $news_userList
        ];

        return $result;
    }

    public function getUser_NewsList(int $page, ?NewsRepository $news, ?UserRepository $user): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($user->id == null) ? [] : ["user_id"=>$user->id];

        $getUser = (new User())->getUsers($user,0);

        if ($user->id == null)
        {
            $fields = ($getUser[0]["id"]) ? ["user_id"=>$getUser[0]["id"]] : [];
        }

        $likeFields = [];

        $news_user = $db->findAll("read_news",$fields,$page, $likeFields);

        $user_newsList = [];

        foreach ($news_user as $relation)
        {
            $NewsRepository = new NewsRepository();
            $NewsRepository->id = $relation["news_id"];
            $getNews = (new News())->getNews(0,$NewsRepository);

            array_push($user_newsList, $getNews[0]);
        }

        $getUser[0]["id"] = "";
        $getUser[0]["password"] = "";

        $result = [
            "user" => $getUser[0],
            "newsList" => $user_newsList
        ];

        return $result;
    }

    public function getRelations(int $page, ?NewsRepository $news, ?UserRepository $user): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["news_id"] = ($news->id == null) ? "" : $news->id;
        $fields["user_id"] = ($user->id == null) ? "" : $user->id;

        $likeFields = [];

        $relations = $db->findAll("read_news",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(NewsRepository $news, UserRepository $user): string
    {
        $db = (new DatabaseFactory())->db;

        $getNews = (new News())->getNews(0,$news);
        $getUser = (new User())->getUser($user,0);

        $fields = [];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];

        $copyRelationControl = $this->getRelations(0, $news, $user);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("read_news",$fields);

        return $createResult;
    }

    public function delete(NewsRepository $news, UserRepository $user): string
    {
        $db = (new DatabaseFactory())->db;

        $getNews = (new News())->getNews(0,$news);
        $getUser = (new User())->getUsers($user,0);

        $fields = [];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];

        $deleteResult = $db->delete("read_news", $fields);

        return $deleteResult;
    }
}