<?php
namespace App\Lib\User;

use App\Lib\Database\DatabaseFactory;
use Cassandra\Date;

class UserWiper
{
    private mixed $id;
    private mixed $user_id;
    private int $status;
    private date $requested_at;

    public function getRequests(?int $status = 0, int $page = 0): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["status"] = ($status) ? $status : "";

        $requests = $db->findAll("userwiper",$fields,$page);

        $lastPage = $requests["lastPage"];
        $contents = $requests["content"];

        $RequestAndUserList = [];
        foreach ($contents as $request)
        {
            $user_id = $request["user_id"];
            $User = new User();
            $UserRepository = new UserRepository();
            $User->id = $user_id;
            $GetUser = $UserRepository->findUser($User);
            $GetUser["password"] = "";

            array_push($RequestAndUserList, [$request,$GetUser]);
        }

        return [
            "lastPage" => $lastPage,
            "content" => $RequestAndUserList
        ];
    }

    public function findRequest(int $user_id): mixed
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["user_id"] = $user_id;

        $requests = $db->find("userwiper",$fields);

        return $requests;
    }

    public function add(int $user_id): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["user_id"] = $user_id;

        $fields["requested_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("userwiper",$fields);

        return $createResult;
    }

    public function edit(int $user_id, int $status): string
    {
        $db = (new DatabaseFactory())->db;
        $User = new User();
        $UserRepository = new UserRepository();

        $whereFields = [];
        $whereFields["user_id"] = $user_id;

        $setFields["status"] = $status;

        $editResult = $db->update("userwiper", $setFields, $whereFields);

        if ($status == 1 && $editResult)
        {
            $User->id = $user_id;
            $editResult = $UserRepository->delete($User);
        }

        return $editResult;
    }

    public function delete(int $id): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $id;

        $deleteResult = $db->delete("userwiper", $whereFields);

        return $deleteResult;
    }
}