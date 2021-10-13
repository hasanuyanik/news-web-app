<?php
namespace App\Lib\Relations;

use App\Lib\Comment\Comment;
use App\Lib\Comment\CommentRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\User\User;
use App\Lib\User\UserRepository;

class User_Comment
{
    public function getUser_CommentList(int $page, ?UserRepository $user, ?CommentRepository $comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($user->id == null) ? [] : ["user_id"=>$user->id];

        $getUser = (new User())->getUsers($user, 0);

        if ($user->id == null)
        {
            $fields = ($getUser[0]["id"]) ? ["user_id"=>$getUser[0]["id"]] : [];
        }

        $likeFields = [];

        $user_comment = $db->findAll("user_comment",$fields,$page, $likeFields);

        $user_commentList = [];

        foreach ($user_comment as $relation)
        {
            $CommentRepository = new CommentRepository();
            $CommentRepository->id = $relation["comment_id"];
            $getComment = (new Comment())->getComments(0, $CommentRepository);
            array_push($user_commentList, $getComment[0]);
        }

        $getUser[0]["id"] = "";
        $getUser[0]["password"] = "";

        $result = [
            "user" => $getUser[0],
            "commentList" => $user_commentList
        ];

        return $result;
    }

    public function getRelations(int $page, ?UserRepository $user, ?CommentRepository $comment): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["comment_id"] = ($comment->id == null) ? "" : $comment->id;
        $fields["user_id"] = ($user->id == null) ? "" : $user->id;

        $likeFields = [];

        $relations = $db->findAll("user_comment",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(UserRepository $user, CommentRepository $comment): string
    {
        $db = (new DatabaseFactory())->db;

        $getComment = (new Comment())->getComments(0,$comment);
        $getUser = (new User())->getUsers($user, 0);

        $fields = [];
        $fields["comment_id"] = ($comment->id) ? $comment->id : $getComment[0]["id"];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];

        $copyRelationControl = $this->getRelations(0, $user, $comment);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("user_comment",$fields);

        return $createResult;
    }

    public function delete(UserRepository $user, CommentRepository $comment): string
    {
        $db = (new DatabaseFactory())->db;

        $getComment = (new Comment())->getComments(0,$comment);
        $getUser = (new User())->getUsers($user, 0);

        $fields = [];
        $fields["comment_id"] = ($comment->id) ? $comment->id : $getComment[0]["id"];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];

        $deleteResult = $db->delete("user_comment", $fields);

        return $deleteResult;
    }
}