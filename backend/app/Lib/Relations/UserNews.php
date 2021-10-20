<?php
namespace App\Lib\Relations;

use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;

class UserNews
{
    public function getUser_NewsList(int $page, ?UserRepository $user, ?NewsRepository $news): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($user->id == null) ? [] : ["user_id"=>$user->id];

        $getUser = (new User())->getUsers($user, 0);

        if ($user->id == null)
        {
            $fields = ($getUser[0]["id"]) ? ["user_id"=>$getUser[0]["id"]] : [];
        }

        $likeFields = [];

        $user_news = $db->findAll("user_news",$fields,$page, $likeFields);

        $user_newsList = [];

        foreach ($user_news as $relation)
        {
            $NewsRepository = new NewsRepository();
            $NewsRepository->id = $relation["news_id"];
            $getNews = (new News())->getNews(0, $NewsRepository);
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

    public function getRelations(int $page, ?UserRepository $user, ?NewsRepository $news): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["user_id"] = ($user->id == null) ? "" : $user->id;
        $fields["news_id"] = ($news->id == null) ? "" : $news->id;

        $likeFields = [];

        $relations = $db->findAll("user_news",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(UserRepository $user, NewsRepository $news): string
    {
        $db = (new DatabaseFactory())->db;

        $getUser = (new User())->getUsers($user, 0);
        $getNews = (new News())->getNews(0, $news);

        $fields = [];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];

        $copyRelationControl = $this->getRelations(0, $news, $user);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("user_news",$fields);

        return $createResult;
    }

    public function delete(UserRepository $user, NewsRepository $news): string
    {
        $db = (new DatabaseFactory())->db;

        $getUser = (new User())->getUsers($user, 0);
        $getNews = (new News())->getNews(0, $news);

        $fields = [];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];

        $deleteResult = $db->delete("user_news", $fields);

        return $deleteResult;
    }
}
