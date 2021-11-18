<?php
namespace App\Lib\Relations;

use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;

class UserNews
{
    public function getUserNewsList(int $page, ?User $User, ?News $News): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($User->id == null) ? [] : ["user_id"=>$User->id];

        $getUser = (new UserRepository())->findUser($User);

        if ($User->id == null)
        {
            $fields = ($getUser["id"]) ? ["user_id"=>$getUser["id"]] : [];
        }

        $likeFields = [];

        $UserNews = $db->findAll("user_news",$fields,$page, $likeFields);

        $UserNewsList = [];

        foreach ($UserNews["content"] as $relation)
        {
            $News = new News();
            $News->id = $relation["news_id"];
            $getNews = (new NewsRepository())->findNews($News);
            array_push($UserNewsList, $getNews);
        }

        $getUser["id"] = "";
        $getUser["password"] = "";

        $result = [
            "user" => $getUser,
            "content" => $UserNewsList
        ];

        return $result;
    }

    public function getUserCategoryNewsList(int $page, ?User $User, ?Category $Category, ?News $News): array
    {
        $db = (new DatabaseFactory())->db;
        $CategoryNews = new CategoryNews();

        $fields = ($User->id == null) ? [] : ["user_id"=>$User->id];

        $getUser = (new UserRepository())->findUser($User);
        $getCategory = (new CategoryRepository())->findCategory($Category);

        $Category->id = $getCategory["id"];
        $Category->url = $getCategory["url"];
        $Category->name = $getCategory["name"];

        if ($User->id == null)
        {
            $fields = ($getUser["id"]) ? ["user_id"=>$getUser["id"]] : [];
        }

        $likeFields = [];

        $subArray = [
            "0" => [
            "table" => "user_news",
            "subTable" => "category_news",
            "fetchColumn" => "news_id",
            "whereColumn" => [
                "category_id" => $Category->id
            ],
            "foreignKeyColumn" => "news_id"
        ]
        ];

        $UserNews = $db->findAll("user_news",$fields,$page, $likeFields, subArray: $subArray);

        $UserNewsList = [];

        foreach ($UserNews["content"] as $relation)
        {
            $News = new News();
            $News->id = $relation["news_id"];
            $categoryRelation = $CategoryNews->getRelations(0, $Category, $News);

            if (count($categoryRelation["content"]) > 0)
            {
                $getNews = (new NewsRepository())->findNews($News);
                array_push($UserNewsList, $getNews);
            }
        }

        $getUser["id"] = "";
        $getUser["password"] = "";

        $result = [
            "user" => $getUser,
            "content" => $UserNewsList,
            "first" => $UserNews["first"],
            "last" => $UserNews["last"],
            "pageNumber" => $page
        ];

        return $result;
    }

    public function getRelations(int $page, ?User $User, ?News $News): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["user_id"] = ($User->id == null) ? "" : $User->id;
        $fields["news_id"] = ($News->id == null) ? "" : $News->id;

        $likeFields = [];

        $relations = $db->findAll("user_news",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(User $User, News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $getUser = (new UserRepository())->findUser($User);
        $getNews = (new NewsRepository())->findNews($News);

        $fields = [];
        $fields["user_id"] = ($User->id) ? $User->id : $getUser["id"];
        $fields["news_id"] = ($News->id) ? $News->id : $getNews["id"];

        $copyRelationControl = $this->getRelations(0, $User, $News);

        if (count($copyRelationControl["content"]) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("user_news",$fields);

        return $createResult;
    }

    public function delete(User $User, News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $getUser = (new UserRepository())->findUser($User);
        $getNews = (new NewsRepository())->findNews($News);

        $fields = [];
        $fields["user_id"] = ($User->id) ? $User->id : $getUser["id"];
        $fields["news_id"] = ($News->id) ? $News->id : $getNews["id"];

        $deleteResult = $db->delete("user_news", $fields);

        return $deleteResult;
    }
}
