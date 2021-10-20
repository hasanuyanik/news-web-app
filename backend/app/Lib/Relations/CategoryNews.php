<?php
namespace App\Lib\Relations;

use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;

class CategoryNews
{
    public function getCategory_NewsList(int $page, ?CategoryRepository $category, ?NewsRepository $NewsRepository): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($category->id == null) ? [] : ["category_id"=>$category->id];

        $getCategory = (new Category())->getCategories(0,$category);

        if ($category->id == null)
        {
            $fields = ($getCategory[0]["id"]) ? ["category_id"=>$getCategory[0]["id"]] : [];
        }
        $categoryName = ($category->name) ? $category->name : $getCategory[0]["name"];

        $likeFields = [];

        $category_news = $db->findAll("category_news",$fields,$page, $likeFields);

        $category_newsList = [];

        foreach ($category_news as $relation)
        {
            $NewsRepository->id = $relation["news_id"];
            $getNews = (new News())->getNews(0, $NewsRepository);
            array_push($category_newsList, $getNews[0]);
        }

        $result = [
            "category" => $getCategory[0],
            "newsList" => $category_newsList
        ];

        return $result;
    }

    public function getRelations(int $page, ?CategoryRepository $category, ?NewsRepository $news): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["category_id"] = ($category->id == null) ? "" : $category->id;
        $fields["news_id"] = ($news->id == null) ? "" : $news->id;

        $likeFields = [];

        $relations = $db->findAll("category_news",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(CategoryRepository $category, NewsRepository $news): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new Category())->getCategories(0,$category);
        $getNews = (new News())->getNews(0, $news);

        $fields = [];
        $fields["category_id"] = ($category->id) ? $category->id : $getCategory[0]["id"];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];

        $copyRelationControl = $this->getRelations(0,$category, $news);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("category_news",$fields);

        return $createResult;
    }

    public function delete(CategoryRepository $category, NewsRepository $news): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new Category())->getCategories(0,$category);
        $getNews = (new News())->getNews(0, $news);

        $fields = [];
        $fields["category_id"] = ($category->id) ? $category->id : $getCategory[0]["id"];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];

        $deleteResult = $db->delete("category_news", $fields);

        return $deleteResult;
    }
}