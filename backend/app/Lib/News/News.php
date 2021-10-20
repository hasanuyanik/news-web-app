<?php
namespace App\Lib\News;

use App\Lib\Database\DatabaseFactory;

class News
{

    public function getNews(int $page, ?NewsRepository $news): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($news->id == null) ? ["news_status" => $news->news_status] : ["id"=>$news->id, "news_status" => $news->news_status];

        $likeFields = [];
        $likeFields["title"] = $news->title;
        $likeFields["description"] = $news->description;
        $likeFields["content"] = $news->content;

        $categories = $db->findAll("news",$fields,$page, $likeFields);

        return $categories;
    }

    public function add(NewsRepository $news): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["title"] = $news->title;
        $fields["description"] = $news->description;
        $fields["content"] = $news->content;

        $copyNewsControl = $this->getNews($news);

        if (count($copyNewsControl) > 0)
        {
            return 0;
        }
        $primaryCopyNews = new NewsRepository();
        $primaryCopyNews->title = $news->title;
        $primaryCopyNews->title = $news->description;
        $primaryCopyNewsControl = $this->getNews($primaryCopyNews);
        if (count($primaryCopyNewsControl) > 0)
        {
            return 0;
        }

        $fields["img"] = $news->img;
        $fields["news_status"] = $news->news_status;
        $fields["created_at"] = date('Y.m.d H:i:s');
        $fields["updated_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("news",$fields);

        return $createResult;
    }

    public function edit(NewsRepository $news): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $news->id;

        $setFields["title"] = $news->title;
        $setFields["description"] = $news->description;
        $setFields["content"] = $news->content;
        $setFields["img"] = $news->img;
        $setFields["news_status"] = $news->news_status;

        $primaryCopyNews = new NewsRepository();
        $primaryCopyNews->title = $news->title;
        $primaryCopyNews->description = $news->description;
        $primaryCopyNewsControl = $this->getNews($primaryCopyNews);
        if (count($primaryCopyNewsControl) > 0)
        {
            return 0;
        }

        $editResult = $db->update("news", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(NewsRepository $news): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $news->id;

        $deleteResult = $db->delete("news", $fields);

        return $deleteResult;
    }
}