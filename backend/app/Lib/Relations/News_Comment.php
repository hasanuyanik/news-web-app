<?php
namespace App\Lib\Relations;

use App\Lib\Comment\Comment;
use App\Lib\Comment\CommentRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\News\News;
use App\Lib\News\NewsRepository;

class News_Comment
{
    public function getNews_CommentList(int $page, ?NewsRepository $news, ?CommentRepository $comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($news->id == null) ? [] : ["news_id"=>$news->id];

        $getNews = (new News())->getNews(0,$news);

        if ($news->id == null)
        {
            $fields = ($getNews[0]["id"]) ? ["news_id"=>$getNews[0]["id"]] : [];
        }

        $likeFields = [];

        $news_comment = $db->findAll("news_comment",$fields,$page, $likeFields);

        $news_commentList = [];

        foreach ($news_comment as $relation)
        {
            $CommentRepository = new CommentRepository();
            $CommentRepository->id = $relation["comment_id"];
            $getComment = (new Comment())->getComments(0, $CommentRepository);
            array_push($news_commentList, $getComment[0]);
        }

        $result = [
            "news" => $getNews[0],
            "commentList" => $news_commentList
        ];

        return $result;
    }

    public function getRelations(int $page, ?NewsRepository $news, ?CommentRepository $comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["comment_id"] = ($comment->id == null) ? "" : $comment->id;
        $fields["news_id"] = ($news->id == null) ? "" : $news->id;

        $likeFields = [];

        $relations = $db->findAll("news_comment",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(NewsRepository $news, CommentRepository $comment): string
    {
        $db = (new DatabaseFactory())->db;

        $getComment = (new Comment())->getComments(0,$comment);
        $getNews = (new News())->getNews(0, $news);

        $fields = [];
        $fields["comment_id"] = ($comment->id) ? $comment->id : $getComment[0]["id"];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];

        $copyRelationControl = $this->getRelations(0, $news, $comment);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("news_comment",$fields);

        return $createResult;
    }

    public function delete(NewsRepository $news, CommentRepository $comment): string
    {
        $db = (new DatabaseFactory())->db;

        $getComment = (new Comment())->getComments(0,$comment);
        $getNews = (new News())->getNews(0, $news);

        $fields = [];
        $fields["comment_id"] = ($comment->id) ? $comment->id : $getComment[0]["id"];
        $fields["news_id"] = ($news->id) ? $news->id : $getNews[0]["id"];

        $deleteResult = $db->delete("news_comment", $fields);

        return $deleteResult;
    }
}