<?php

namespace App\Lib\Database\Engine;

interface DatabaseI
{
    public function findAll(string $table, array $fields, int $page, ?array $likeFields, ?array $columnsToFetch): mixed;
    public function find(string $table, array $fields, ?array $columnsToFetch): mixed;
    public function add(string $table, array $fields): string;
    public function update(string $table, array $setFields, array $whereFields): string;
    public function delete(string $table, array $fields): string;
}