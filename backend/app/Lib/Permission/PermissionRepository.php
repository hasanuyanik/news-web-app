<?php
namespace App\Lib\Permission;

use App\Lib\Database\DatabaseFactory;

class PermissionRepository
{
    public function getPermissions(int $page, ?Permission $permission): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($permission->id == null) ? [] : ["id"=>$permission->id];

        $likeFields = [];
        $likeFields["name"] = $permission->name;

        $permissions = $db->findAll("permissions",$fields,$page, $likeFields);

        return $permissions;
    }

    public function add(Permission $permission): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $permission->name;
        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("permissions",$fields);

        return $createResult;
    }

    public function edit(Permission $permission): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $permission->id;

        $setFields["name"] = $permission->name;

        $editResult = $db->update("permissions", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(Permission $permission): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $permission->id;

        $deleteResult = $db->delete("permissions", $fields);

        return $deleteResult;
    }
}