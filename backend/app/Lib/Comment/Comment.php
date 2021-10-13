<?php
namespace App\Lib\Comment;

use App\Lib\Database\DatabaseFactory;

class Comment implements CommentI
{

    public function getComments(int $page, ?CommentRepository $comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($comment->id == null) ? [] : ["id"=>$comment->id];

        $likeFields = [];
        $likeFields["name"] = $comment->name;
        $likeFields["comment"] = $comment->comment;

        $categories = $db->findAll("comment",$fields,$page, $likeFields);

        return $categories;
    }

    public function add(CommentRepository $comment): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $comment->name;
        $fields["comment"] = $comment->comment;
        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("comment",$fields);

        return $createResult;
    }

    public function edit(CommentRepository $comment): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $comment->id;

        $setFields["name"] = $comment->name;
        $setFields["comment"] = $comment->comment;

        $editResult = $db->update("comment", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(CommentRepository $comment): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $comment->id;

        $deleteResult = $db->delete("comment", $fields);

        return $deleteResult;
    }
}