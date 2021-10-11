<?php

namespace App\Lib\Database\Engine;

use App\Lib\Database\Builder\MySQLQueryBuilder;

class MySQLDatabase implements DatabaseI
{
    private $db;
    private $dataPerPage;
    public function __construct(
    )
    {
        $this->db = [];
        $configdir = __DIR__."/../../../../config.php";

        if (file_exists($configdir))
        {
            $config = require $configdir;
            $host = $config["host"];
            $dbname = $config["dbname"];
            $user = $config["user"];
            $password = $config["password"];
            $this->dataPerPage = $config["data_per_page"];

            $this->db = new \PDO("mysql:host=$host;dbname=$dbname",$user,$password);
        }

    }

    public function findAll(string $table, array $fields, int $page, ?array $likeFields = []): mixed
    {
        $start = $page*$this->dataPerPage;
        $end = $start+$this->dataPerPage;

        $QueryBuilder = new MySQLQueryBuilder();
        $QueryBuilder->select($table,$fields)->like($likeFields)->limit($start,$end);

        $query = $QueryBuilder->patch;
        $statement = $this->db->prepare(query: $query);
        foreach ($fields as $param => $value) {
            $statement->bindValue(":$param", $value);
        }

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(string $table, array $fields): mixed
    {
        $QueryBuilder = new MySQLQueryBuilder();
        $QueryBuilder->select($table,$fields);

        $statement = $this->db->prepare($QueryBuilder->patch);
        foreach ($fields as $param => $value) {
            $statement->bindValue(":$param", $value);
        }

        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function add(string $table, array $fields): string
    {
        $QueryBuilder = new MySQLQueryBuilder();
        $QueryBuilder->add($table, $fields);

        $statement = $this->db->prepare($QueryBuilder->patch);
        foreach ($fields as $param => $value) {
            $statement->bindValue(":$param", $value);
        }

        $result = $statement->execute();

        return $result;
    }

    public function update(string $table, array $setFields, array $whereFields): string
    {
        $QueryBuilder = new MySQLQueryBuilder();
        $QueryBuilder->update($table, $setFields, $whereFields);
        $statement = $this->db->prepare($QueryBuilder->patch);
        foreach ($setFields as $param => $value) {
            $statement->bindValue(":$param", $value);
        }

        foreach ($whereFields as $param => $value) {
            $statement->bindValue(":$param", $value);
        }

        $result = $statement->execute();

        return $result;
    }

    public function delete(string $table, array $fields): string
    {
        $QueryBuilder = new MySQLQueryBuilder();
        $QueryBuilder->delete($table, $fields);

        $statement = $this->db->prepare($QueryBuilder->patch);
        foreach ($fields as $param => $value) {
            $statement->bindValue(":$param", $value);
        }

        $result = $statement->execute();

        return $result;
    }
}