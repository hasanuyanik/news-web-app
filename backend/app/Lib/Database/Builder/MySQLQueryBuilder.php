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

    public function select(string $table, ?array $whereFields, ?array $columnsToFetch, ?array $joinArray, ?array $subArray): QueryBuilderI
    {
        $columns = (count($columnsToFetch)) ? $this->serialize("columnsToFetch", $columnsToFetch) : "*";

        $whereSerialize = $this->serialize("where", $whereFields);

        $subQuery = (count($subArray) > 0) ? $this->subQuery($subArray).(($whereSerialize) ? " and " : "") : "";

        $joinQuery = (count($joinArray) > 0) ? $this->joinQuery($joinArray) : "";

        $where = ($whereSerialize) ? " WHERE ".$subQuery." ".$whereSerialize : (($subQuery != "") ? $subQuery : "");

        $this->patch .= "SELECT $columns FROM ".$table.$joinQuery.$where;
        return $this;
    }

    public function dataCount(string $table, ?array $whereFields, ?array $joinArray, ?array $subArray): QueryBuilderI
    {
        $serializeWhere = $this->serialize("where", $whereFields);

        $subQuery = (count($subArray) > 0) ? $this->subQuery($subArray).(($serializeWhere) ? " and " : "") : "";

        $joinQuery = (count($joinArray) > 0) ? $this->joinQuery($joinArray) : "";

        $where = (count($whereFields) > 0 && $serializeWhere != "") ? " WHERE ".$subQuery." ".$serializeWhere : (($subQuery != "") ? " WHERE ".$subQuery : "");

        $this->patch .= "SELECT count(id) as 'dataCount' FROM ".$table.$joinQuery.$where;

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
        $where = (count($likeFields) > 0 && $likeSerialize != null) ? ((str_contains($this->patch, " WHERE ")) ? " and ".$likeSerialize : " WHERE ".$likeSerialize) : "";
        $this->patch .= $where;
        return $this;
    }

    public function limit(int $start, int $end): QueryBuilderI
    {
        $this->patch .= " LIMIT $start, $end";
        return $this;
    }

    public function subQuery(array $subDatas): string
    {
        $query = "";

        foreach ($subDatas as $item)
        {
            $table = $item["table"];
            $subTable = $item["subTable"];
            $fetchColumn = $item["fetchColumn"];
            $whereColumn = $item["whereColumn"];
            $foreignKeyColumn = $item["foreignKeyColumn"];

            $serializeWhere = $this->serialize("where", $whereColumn);

            $query = (($query != "") ? $query." and ":"")."$foreignKeyColumn=(SELECT $fetchColumn FROM $subTable WHERE $fetchColumn=$table.$foreignKeyColumn and $serializeWhere)";

        }

        return $query;
    }

    public function joinQuery(array $joinDatas): string
    {
        $query = "";

        foreach ($joinDatas as $item)
        {
            $joinTable = $item["table"];
            $primaryColumn = $item["primaryColumn"];
            $foreignKeyColumn = $item["foreignKeyColumn"];

            $query = $query."INNER JOIN $joinTable ON $primaryColumn=$foreignKeyColumn ";
        }

        return $query;
    }

    public function serialize(string $type, array $fields): string
    {
        $propertiesCounter = 1;
        $result = '';

        foreach ($fields as $column => $value)
        {
            if ($value != null || $value != "") {

                if ($propertiesCounter > 1) {
                    if ($type == 'where' || $type == 'like') {
                        $result .= " and ";
                    } else {
                        $result .= ",";
                    }
                }

                if ($type == 'value') {
                    $result .= ":$column";
                } elseif ($type == 'column') {
                    $result .= $column;
                } elseif ($type == 'set' || $type == 'where') {
                    if (is_array($value))
                    {
                        $result .= "$column$value[0]:$column";
                    }
                    else
                    {
                        $result .= "$column=:$column";
                    }
                } elseif ($type == 'like') {
                    $result .= "$column LIKE '%$value%'";
                } elseif ($type == 'columnsToFetch') {
                    $result .= "$column as $value";
                }
                $propertiesCounter++;
            }
        }

        return $result;
    }
}