<?php

namespace App\Lib\User;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Encoder\Encoder;

class UserRepository
{
    public function getUsers(?User $user, int $page = 0): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($user->id == null) ? [] : ["id"=>$user->id];

        $likeFields = [];
        $likeFields["username"] = $user->username;
        $likeFields["fullname"] = $user->fullname;
        $likeFields["email"] = $user->email;
        $likeFields["phone"] = $user->phone;

        $columnsToFetch = [
            "id" => "id",
            "username" => "username",
            "fullname" => "fullname",
            "email" => "email",
            "phone" => "phone"
        ];

        $users = $db->findAll("user",$fields,$page, $likeFields, columnsToFetch: $columnsToFetch);

        return $users;
    }

    public function findUser(?User $user): mixed
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($user->id == null) ? (($user->username == "") ? [] : ["username" => $user->username]) : ["id" => $user->id];

        $columnsToFetch = [
            "id" => "id",
            "username" => "username",
            "fullname" => "fullname",
            "email" => "email",
            "phone" => "phone",
            "password" => "password"
        ];

        $users = $db->find("user",$fields, columnsToFetch: $columnsToFetch);

        return $users;
    }

    public function UniqueUsername(string $username): mixed
    {
        $db = (new DatabaseFactory())->db;

        $likeFields = [];

        $fields["username"] = $username;

        $users = $db->find("user",$fields, $likeFields);

        return $users;
    }

    public function add(User $user): string
    {
        $db = (new DatabaseFactory())->db;

        $encoder = new Encoder();

        $fields = [];
        $fields["username"] = $user->username;
        $fields["fullname"] = $user->fullname;
        $fields["email"] = $user->email;
        $fields["phone"] = $user->phone;

        $copyAccountControl = $this->findUser($user);

        if ($copyAccountControl != false)
        {
            return 0;
        }
        $primaryCopyUser = new User();
        $primaryCopyUser->username = $user->username;
        $primaryCopyUser->email = $user->email;
        $primaryCopyUser->phone = $user->phone;
        $primaryCopyAccountControl = $this->findUser($primaryCopyUser);
        if ($primaryCopyAccountControl != false)
        {
            return 0;
        }

        $password = $encoder->salt($encoder->encode($user->password));
        $fields["password"] = $password;

        $fields["created_at"] = date('Y.m.d H:i:s');
        $fields["updated_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("user",$fields);

        return $createResult;
    }

    public function edit(User $user): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $user->id;

        $setFields["username"] = $user->username;
        $setFields["fullname"] = $user->fullname;
        $setFields["email"] = $user->email;
        $setFields["phone"] = $user->phone;
        $setFields["password"] = $user->password;

        $setFields["updated_at"] = date('Y.m.d H:i:s');

        $editResult = $db->update("user", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(User $user): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $user->id;

        $deleteResult = $db->delete("user", $fields);

        return $deleteResult;
    }

}
