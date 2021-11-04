<?php
namespace App\Lib\Relations;

use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;

class CategoryNews
{
    public function getCategoryNewsList(int $page, ?Category $Category, ?News $News): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($Category->id == null) ? [] : ["category_id"=>$Category->id];

        $getCategory = (new CategoryRepository())->getCategories(0,$Category);

        if ($Category->id == null)
        {
            $fields = ($getCategory[0]["id"]) ? ["category_id"=>$getCategory[0]["id"]] : [];
        }
        $categoryName = ($Category->name) ? $Category->name : $getCategory[0]["name"];

        if ($News->id == null)
        {
            $fields = ($getCategory[0]["id"]) ? ["category_id"=>$getCategory[0]["id"]] : [];
        }


        $likeFields = [];

        $categoryNews = $db->findAll("category_news",$fields,$page, $likeFields);

        $categoryNewsList = [];

        foreach ($categoryNews as $relation)
        {
            $News->id = $relation["news_id"];
            $getNews = (new NewsRepository())->getNews(0, $News);
            array_push($categoryNewsList, $getNews[0]);
        }

        $result = [
            "category" => $getCategory[0],
            "newsList" => $categoryNewsList
        ];

        return $result;
    }

    public function getRelations(int $page, ?Category $Category, ?News $News): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["category_id"] = ($Category->id == null) ? "" : $Category->id;
        $fields["news_id"] = ($News->id == null) ? "" : $News->id;

        $likeFields = [];

        $relations = $db->findAll("category_news",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(Category $Category, News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new CategoryRepository())->findCategory($Category);
        $getNews = (new NewsRepository())->findNews($News);

        $fields = [];
        $fields["category_id"] = ($Category->id) ? $Category->id : $getCategory["id"];
        $fields["news_id"] = ($News->id) ? $News->id : $getNews["id"];

        $copyRelationControl = $this->getRelations(0,$Category, $News);

        if (count($copyRelationControl["content"]) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("category_news",$fields);

        return $createResult;
    }

    public function delete(Category $Category, News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new CategoryRepository())->findCategory($Category);
        $getNews = (new NewsRepository())->findNews($News);

        $fields = [];
        $fields["category_id"] = ($Category->id) ? $Category->id : $getCategory["id"];
        $fields["news_id"] = ($News->id) ? $News->id : $getNews["id"];

        $deleteResult = $db->delete("category_news", $fields);

        return $deleteResult;
    }
}