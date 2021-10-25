<?php

namespace App\Lib\Database\Builder;

class MySQLQueryBuilder implements QueryBuilderI
{
    public $patch;
    public function add(string $table, array $fields): QueryBuilderI
    {
        $column = (count($fields)) ? $this->serialize("column", $fields) : "";
        $value = (count($fields)) ? $this->serialize("value", $fields) : "";

        $this->patch .= "INSERT INTO ".$table."(".$column.") values(".$value.")";
        return $this;
    }

    public function select(string $table, ?array $whereFields, ?array $columnsToFetch): QueryBuilderI
    {
        $columns = (count($columnsToFetch)) ? $this->serialize("columnsToFetch", $columnsToFetch) : "*";
        $where = (count($whereFields)) ? " WHERE ".$this->serialize("where", $whereFields) : "";
        $this->patch .= "SELECT $columns FROM ".$table.$where;
        return $this;
    }

    public function dataCount(string $table, ?array $whereFields): QueryBuilderI
    {
        $where = (count($whereFields)) ? " WHERE ".$this->serialize("where", $whereFields) : "";
        $this->patch .= "SELECT count(id) as 'dataCount' FROM ".$table.$where;
        return $this;
    }

    public function update(string $table, array $setFields, array $whereFields): QueryBuilderI
    {
        $set = (count($setFields)) ? " SET ".$this->serialize("set", $setFields) : "";
        $where = (count($whereFields)) ? " WHERE ".$this->serialize("where", $whereFields) : "";
        $this->patch .= "UPDATE ".$table.$set.$where;
        return $this;
    }

    public function delete(string $table, array $whereFields): QueryBuilderI
    {
        $where = (count($whereFields)) ? " WHERE ".$this->serialize("where", $whereFields) : "";
        $this->patch .= "DELETE FROM ".$table.$where;
        return $this;
    }

    public function like(array $likeFields): QueryBuilderI
    {
        $likeSerialize = $this->serialize("like", $likeFields);
        $where = (count($likeFields)) ? ((str_contains($this->patch, " WHERE ")) ? " and ".$likeSerialize : " WHERE ".$likeSerialize) : "";
        $this->patch .= $where;
        return $this;
    }

    public function limit(int $start, int $end): QueryBuilderI
    {
        $this->patch .= " LIMIT $start, $end";
        return $this;
    }

    public function serialize(string $type, array $fields): string
    {
        $propertiesCounter = 1;
        $result = '';

        foreach ($fields as $column => $value)
        {
            if ($propertiesCounter > 1)
            {
                if ($type == 'where' || $type == 'like')
                {
                    $result .= " and ";
                }
                else
                {
                    $result .= ",";
                }
            }

            if ($type == 'value')
            {
                $result .= ":$column";
            }
            elseif ($type == 'column')
            {
                $result .= $column;
            }
            elseif ($type == 'set' || $type == 'where')
            {
                $result .= "$column=:$column";
            }
            elseif ($type == 'like')
            {
                $result .= "$column LIKE '%$value%'";
            }
            elseif ($type == 'columnsToFetch')
            {
                $result .= "$column as $value";
            }
            $propertiesCounter++;
        }

        return $result;
    }
}