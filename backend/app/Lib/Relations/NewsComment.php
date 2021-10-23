<?php
namespace App\Lib\Relations;

use App\Lib\Comment\Comment;
use App\Lib\Comment\CommentRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;

class NewsComment
{
    public function getNewsCommentList(int $page, ?News $News, ?Comment $Comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($News->id == null) ? [] : ["news_id"=>$News->id];

        $getNews = (new NewsRepository())->getNews(0,$News);

        if ($News->id == null)
        {
            $fields = ($getNews[0]["id"]) ? ["news_id"=>$getNews[0]["id"]] : [];
        }

        $likeFields = [];

        $newsComment = $db->findAll("news_comment",$fields,$page, $likeFields);

        $newsCommentList = [];

        foreach ($newsComment as $relation)
        {
            $Comment = new Comment();
            $Comment->id = $relation["comment_id"];
            $getComment = (new CommentRepository())->getComments(0, $Comment);
            array_push($newsCommentList, $getComment[0]);
        }

        $result = [
            "news" => $getNews[0],
            "commentList" => $newsCommentList
        ];

        return $result;
    }

    public function getRelations(int $page, ?News $News, ?Comment $Comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["comment_id"] = ($Comment->id == null) ? "" : $Comment->id;
        $fields["news_id"] = ($News->id == null) ? "" : $News->id;

        $likeFields = [];

        $relations = $db->findAll("news_comment",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(News $News, Comment $Comment): string
    {
        $db = (new DatabaseFactory())->db;

        $getComment = (new CommentRepository())->getComments(0,$Comment);
        $getNews = (new NewsRepository())->getNews(0, $News);

        $fields = [];
        $fields["comment_id"] = ($Comment->id) ? $Comment->id : $getComment[0]["id"];
        $fields["news_id"] = ($News->id) ? $News->id : $getNews[0]["id"];

        $copyRelationControl = $this->getRelations(0, $News, $Comment);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("news_comment",$fields);

        return $createResult;
    }

    public function delete(News $News, Comment $Comment): string
    {
        $db = (new DatabaseFactory())->db;

        $getComment = (new CommentRepository())->getComments(0, $Comment);
        $getNews = (new NewsRepository())->getNews(0, $News);

        $fields = [];
        $fields["comment_id"] = ($Comment->id) ? $Comment->id : $getComment[0]["id"];
        $fields["news_id"] = ($News->id) ? $News->id : $getNews[0]["id"];

        $deleteResult = $db->delete("news_comment", $fields);

        return $deleteResult;
    }
}