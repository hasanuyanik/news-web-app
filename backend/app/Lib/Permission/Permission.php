<?php
namespace App\Lib\Permission;

use App\Lib\Database\DatabaseFactory;

class Permission implements PermissionI
{
    public function getPermissions(int $page, ?PermissionRepository $permissionRepository): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($permissionRepository->id == null) ? [] : ["id"=>$permissionRepository->id];

        $likeFields = [];
        $likeFields["name"] = $permissionRepository->name;

        $permissions = $db->findAll("permissions",$fields,$page, $likeFields);

        return $permissions;
    }

    public function add(PermissionRepository $permissionRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $permissionRepository->name;
        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("permissions",$fields);

        return $createResult;
    }

    public function edit(PermissionRepository $permissionRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $permissionRepository->id;

        $setFields["name"] = $permissionRepository->name;

        $editResult = $db->update("permissions", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(PermissionRepository $permissionRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $permissionRepository->id;

        $deleteResult = $db->delete("permissions", $fields);

        return $deleteResult;
    }
}