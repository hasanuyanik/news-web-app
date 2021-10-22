<?php
namespace App\Lib\Role;

use App\Lib\Database\DatabaseFactory;

class RoleRepository
{
    public function getRoles(int $page, ?Role $role): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($role->id == null) ? [] : ["id"=>$role->id];

        $likeFields = [];
        $likeFields["name"] = $role->name;

        $roles = $db->findAll("roles",$fields,$page, $likeFields);

        return $roles;
    }

    public function add(Role $role): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $role->name;
        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("roles",$fields);

        return $createResult;
    }

    public function edit(Role $role): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $role->id;

        $setFields["name"] = $role->name;

        $editResult = $db->update("roles", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(Role $role): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $role->id;

        $deleteResult = $db->delete("roles", $fields);

        return $deleteResult;
    }
}