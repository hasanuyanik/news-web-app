<?php
namespace App\Lib\News;

use App\Lib\Database\DatabaseFactory;

class NewsRepository
{
    public function getNews(int $page, ?News $News): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($News->id == null) ? ["news_status" => $News->news_status] : ["id"=>$News->id, "news_status" => $News->news_status];

        $likeFields = [];
        $likeFields["title"] = $News->title;
        $likeFields["description"] = $News->description;
        $likeFields["content"] = $News->content;

        $newsList = $db->findAll("news",$fields,$page, $likeFields);

        $contentList = [];

        foreach ($newsList["content"] as $param => $value)
        {
            $value["title"] = stripcslashes($value["title"]);
            $value["description"] = stripcslashes($value["description"]);
            $value["content"] = stripcslashes($value["content"]);
            array_push($contentList, $value);
        }

        $newsList["content"] = $contentList;

        return $newsList;
    }

    public function findNews(?News $news): mixed
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = ($news->id == null) ? "" : $news->id;
        $fields["title"] = ($news->title == null) ? "" : $news->title;
        $fields["url"] = ($news->url == null) ? "" : $news->url;

        $findNews = $db->find("news",$fields);

        $findNews["title"] = stripcslashes($findNews["title"]);
        $findNews["description"] = stripcslashes($findNews["description"]);
        $findNews["content"] = stripcslashes($findNews["content"]);

        return $findNews;
    }

    public function add(News $News): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["title"] = addcslashes($News->title, "'");
        $fields["description"] = addcslashes($News->description, "'");
        $fields["content"] = addcslashes($News->content, "'");
        $fields["url"] = $News->url;

        $copyNewsControl = $this->findNews($News);

        if ($copyNewsControl)
        {
            return 0;
        }
        $primaryCopyNews = new News();
        $primaryCopyNews->title = addcslashes($News->title, "'");
        $primaryCopyNews->url = $News->url;
        $primaryCopyNewsControl = $this->findNews($primaryCopyNews);

        if ($primaryCopyNewsControl)
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

        $setFields["title"] = addcslashes($News->title, "'");
        $setFields["description"] = addcslashes($News->description, "'");
        $setFields["content"] = addcslashes($News->content, "'");
        $setFields["img"] = $News->img;
        $setFields["news_status"] = $News->news_status;

        $primaryCopyNews = new News();
        $primaryCopyNews->title = addcslashes($News->title, "'");
        $primaryCopyNews->description = addcslashes($News->description, "'");
        $primaryCopyNewsControl = $this->getNews(0, $primaryCopyNews);

        if ((count($primaryCopyNewsControl["content"]) > 0) && ($primaryCopyNewsControl["content"][0]["id"] != $News->id))
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