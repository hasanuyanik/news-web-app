<?php

namespace App\Lib\Database\Engine;

use App\Lib\Database\Builder\MySQLQueryBuilder;
use App\Lib\Database\DataType\JoinArray;
use App\Lib\Database\DataType\JoinData;

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



    public function findAll(string $table, array $fields, int $page, ?array $likeFields = [], ?array $columnsToFetch = [], ?array $joinArray = [], ?array $subArray = []): mixed
    {
        $CountQueryBuilder = new MySQLQueryBuilder();
        $CountQueryBuilder->dataCount($table,$fields, $joinArray,$subArray)->like($likeFields);
        $CountQuery = $CountQueryBuilder->patch;

        $countStatement = $this->db->prepare(query: $CountQuery);
        foreach ($fields as $param => $value) {
            if ($value != null || $value != "")
            {
                if (is_array($value))
                {
                    $countStatement->bindValue(":$param", $value[1]);
                }
                else
                {
                    $countStatement->bindValue(":$param", $value);
                }
            }
        }

        foreach ($subArray as $param => $whereCol) {
            $whereColumn = $whereCol["whereColumn"];
            foreach ($whereColumn as $parameter => $value)
            {
                if ($value != null || $value != "")
                {
                    if (is_array($value))
                    {
                        $countStatement->bindValue(":$parameter", $value[1]);
                    }
                    else
                    {
                        $countStatement->bindValue(":$parameter", $value);
                    }
                }
            }
        }

        $countStatement->execute();

        $dataCount = $countStatement->fetch(\PDO::FETCH_ASSOC);

        $pageCount = ceil($dataCount["dataCount"] / $this->dataPerPage);

        $pageNumber = ($page < 1) ? 1 : (($page > $pageCount) ? (($pageCount > 0) ? $pageCount : 1) : $page);
        $start = ($pageNumber-1)*$this->dataPerPage;
        $end = $this->dataPerPage;

        $first = ($pageNumber > 1) ? ($pageNumber-1) : "";
        $last = ($pageCount > $pageNumber) ? $pageCount : "";

        $QueryBuilder = new MySQLQueryBuilder();
        $QueryBuilder->select($table,$fields,$columnsToFetch,$joinArray,$subArray)->like($likeFields)->limit($start,$end);

        $query = $QueryBuilder->patch;

        $statement = $this->db->prepare(query: $query);
        foreach ($fields as $param => $value) {
            if ($value != null || $value != "")
            {
                if (is_array($value)) {
                    $statement->bindValue(":$param", $value[1]);
                }
                else
                {
                    $statement->bindValue(":$param", $value);
                }
            }
        }

        foreach ($subArray as $param => $whereCol) {
            $whereColumn = $whereCol["whereColumn"];
            foreach ($whereColumn as $parameter => $value)
            {
                if ($value != null || $value != "")
                {
                    if (is_array($value))
                    {
                        $statement->bindValue(":$parameter", $value[1]);
                    }
                    else
                    {
                        $statement->bindValue(":$parameter", $value);
                    }
                }
            }
        }

        $statement->execute();

        $content = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return [
            "content" => $content,
            "pageNumber" =>  $pageNumber,
            "first" => $first,
            "last" => $last
        ];
    }

    public function find(string $table, array $fields, ?array $columnsToFetch = [], ?array $joinArray = [], ?array $subArray = []): mixed
    {
        $QueryBuilder = new MySQLQueryBuilder();
        $QueryBuilder->select($table,$fields,$columnsToFetch, $joinArray,$subArray);

        $statement = $this->db->prepare($QueryBuilder->patch);



        foreach ($fields as $param => $value)
        {
            if ($value != null || $value != "")
            {
                if (is_array($value))
                {
                    $statement->bindValue(":$param", $value[1]);
                }
                else
                {
                    $statement->bindValue(":$param", $value);
                }
            }
        }

        foreach ($subArray as $param => $whereCol) {
            $whereColumn = $whereCol["whereColumn"];
            foreach ($whereColumn as $parameter => $value)
            {
                if ($value != null || $value != "")
                {
                    if (is_array($value))
                    {
                        $statement->bindValue(":$parameter", $value[1]);
                    }
                    else
                    {
                        $statement->bindValue(":$parameter", $value);
                    }
                }
            }
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