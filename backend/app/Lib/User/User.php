<?php

namespace App\Lib\User;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Database\MySQLDatabaseFactory;
use UserI;

class User implements UserI
{
    private mixed $id;
    private string $username;
    private string $fullname;
    private string $password;
    private string $email;
    private string $phone;
    private date $created_at;
    private date $updated_at;

    public function index()
    {
        echo "INDEX";
    }

    public function getUsers(?UserI $user): array
    {
        $db = new MySQLDatabaseFactory();



        $db->createDBHandler()->findAll();
        return [1,2,3];
    }

    public function add(User $user): string
    {

    }

    public function edit(User $user): string
    {

    }

    public function delete(User $user): string
    {

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
