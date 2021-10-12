<?php

namespace App\Lib\User;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Encoder\Encoder;

class User implements UserI
{
    private mixed $id;
    private string $username;
    private string $fullname;
    private string $password;
    private string $email;
    private string $phone;
    private $created_at;
    private $updated_at;

    public function index()
    {
        echo "INDEX";
    }

    public function getUsers(?UserRepository $user, int $page = 0): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($user->id == null) ? [] : ["id"=>$user->id];

        $likeFields = [];
        $likeFields["username"] = $user->username;
        $likeFields["fullname"] = $user->fullname;
        $likeFields["email"] = $user->email;
        $likeFields["phone"] = $user->phone;

        $users = $db->findAll("user",$fields,$page, $likeFields);

        return $users;
    }

    public function add(UserRepository $user): string
    {
        $db = (new DatabaseFactory())->db;

        $encoder = new Encoder();

        $fields = [];
        $fields["username"] = $user->username;
        $fields["fullname"] = $user->fullname;
        $fields["email"] = $user->email;
        $fields["phone"] = $user->phone;

        $copyAccountControl = $this->getUsers($user);

        if (count($copyAccountControl) > 0)
        {
            return 0;
        }
        $primaryCopyUser = new UserRepository();
        $primaryCopyUser->username = $user->username;
        $primaryCopyUser->email = $user->email;
        $primaryCopyUser->phone = $user->phone;
        $primaryCopyAccountControl = $this->getUsers($primaryCopyUser);
        if (count($primaryCopyAccountControl) > 0)
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

    public function edit(UserRepository $user): string
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

    public function delete(UserRepository $user): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $user->id;

        $deleteResult = $db->delete("user", $fields);

        return $deleteResult;
    }

    /**
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return date
     */
    public function getCreatedAt(): date
    {
        return $this->created_at;
    }

    /**
     * @param date $created_at
     */
    public function setCreatedAt(date $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return date
     */
    public function getUpdatedAt(): date
    {
        return $this->updated_at;
    }

    /**
     * @param date $updated_at
     */
    public function setUpdatedAt(date $updated_at): void
    {
        $this->updated_at = $updated_at;
    }



}
