<?php
namespace App\Lib\News;

use App\Lib\Database\DatabaseFactory;

class NewsRepository
{
    public function getNews(int $page, ?News $News): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($News->id == null) ? ["news_status" => $News->news_status] : ["id"=>$News->id, "news_status" => $news->news_status];

        $likeFields = [];
        $likeFields["title"] = $News->title;
        $likeFields["description"] = $News->description;
        $likeFields["content"] = $News->content;

        $categories = $db->findAll("news",$fields,$page, $likeFields);

        return $categories;
    }

    public function add(News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["title"] = $News->title;
        $fields["description"] = $News->description;
        $fields["content"] = $News->content;

        $copyNewsControl = $this->getNews(0, $News);

        if (count($copyNewsControl) > 0)
        {
            return 0;
        }
        $primaryCopyNews = new News();
        $primaryCopyNews->title = $News->title;
        $primaryCopyNews->title = $News->description;
        $primaryCopyNewsControl = $this->getNews(0, $primaryCopyNews);
        if (count($primaryCopyNewsControl) > 0)
        {
            return 0;
        }

        $fields["img"] = $News->img;
        $fields["news_status"] = $News->news_status;
        $fields["created_at"] = date('Y.m.d H:i:s');
        $fields["updated_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("news",$fields);

        return $createResult;
    }

    public function edit(News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $News->id;

        $setFields["title"] = $News->title;
        $setFields["description"] = $News->description;
        $setFields["content"] = $News->content;
        $setFields["img"] = $News->img;
        $setFields["news_status"] = $News->news_status;

        $primaryCopyNews = new News();
        $primaryCopyNews->title = $News->title;
        $primaryCopyNews->description = $News->description;
        $primaryCopyNewsControl = $this->getNews(0, $primaryCopyNews);
        if (count($primaryCopyNewsControl) > 0)
        {
            return 0;
        }

        $editResult = $db->update("news", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $News->id;

        $deleteResult = $db->delete("news", $fields);

        return $deleteResult;
    }
}