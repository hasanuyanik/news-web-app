<?php
namespace App\Lib\Comment;

use App\Lib\Database\DatabaseFactory;

class CommentRepository
{
    public function getComments(int $page, ?Comment $Comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($Comment->id == null) ? [] : ["id"=>$Comment->id];

        $likeFields = [];
        $likeFields["name"] = $Comment->name;
        $likeFields["comment"] = $Comment->comment;

        $categories = $db->findAll("comment",$fields,$page, $likeFields);

        return $categories;
    }

    public function add(Comment $Comment): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $Comment->name;
        $fields["comment"] = $Comment->comment;
        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("comment",$fields);

        return $createResult;
    }

    public function edit(Comment $Comment): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $Comment->id;

        $setFields["name"] = $Comment->name;
        $setFields["comment"] = $Comment->comment;

        $editResult = $db->update("comment", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(Comment $Comment): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $Comment->id;

        $deleteResult = $db->delete("comment", $fields);

        return $deleteResult;
    }
}