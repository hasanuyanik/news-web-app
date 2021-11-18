<?php

namespace App\Lib\Database\Builder;

interface QueryBuilderI
{
    public function add(string $table, array $fields): QueryBuilderI;
    public function select(string $table, ?array $whereFields, ?array $columnsToFetch, ?array $joinArray, ?array $subArray): QueryBuilderI;
    public function dataCount(string $table, ?array $whereFields, ?array $joinArray, ?array $subArray): QueryBuilderI;
    public function update(string $table, array $setFields, array $whereFields): QueryBuilderI;
    public function delete(string $table, array $whereFields): QueryBuilderI;
    public function limit(int $start, int $end): QueryBuilderI;
    public function like(array $likeFields): QueryBuilderI;
    public function serialize(string $type, array $fields): string;
}