<?php
namespace App\Lib\Relations;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Permission\Permission;
use App\Lib\Permission\PermissionRepository;
use App\Lib\Role\Role;
use App\Lib\Role\RoleRepository;

class RolePermission
{
    public function getRole_PermissionList(int $page, ?Role $role, ?Permission $permission): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($role->id == null) ? [] : ["role_id"=>$role->id];

        $getRole = (new RoleRepository())->getRoles(0,$role);

        if ($role->id == null)
        {
            $fields = ($getRole[0]["id"]) ? ["role_id"=>$getRole[0]["id"]] : [];
        }

        $likeFields = [];

        $roles_permission = $db->findAll("roles_permission",$fields,$page, $likeFields);

        $roles_permissionList = [];

        foreach ($roles_permission as $relation)
        {
            $permission->id = $relation["permission_id"];
            $getPermission = (new PermissionRepository())->getPermissions(0, $permission);
            array_push($roles_permissionList, $getPermission[0]);
        }

        $result = [
            "role" => $getRole[0],
            "permissionList" => $roles_permissionList
        ];

        return $result;
    }

    public function add(Role $role, Permission $permission): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new RoleRepository())->getRoles(0,$role);
        $getPermission = (new PermissionRepository())->getPermissions(0, $permission);

        $fields = [];
        $fields["role_id"] = ($role->id) ? $role->id : $getRole[0]["id"];
        $fields["permission_id"] = ($permission->id) ? $permission->id : $getPermission[0]["id"];

        $copyRelationControl = $this->getRelations(0,$role, $permission);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("roles_permission",$fields);

        return $createResult;
    }

    public function delete(Role $role, Permission $permission): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new RoleRepository())->getRoles(0,$role);
        $getPermission = (new PermissionRepository())->getPermissions(0, $permission);

        $fields = [];
        $fields["role_id"] = ($role->id) ? $role->id : $getRole[0]["id"];
        $fields["permission_id"] = ($permission->id) ? $permission->id : $getPermission[0]["id"];

        $deleteResult = $db->delete("roles_permission", $fields);

        return $deleteResult;
    }

    public function getRelations(int $page, ?Role $role, ?Permission $permission): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["role_id"] = ($role->id == null) ? "" : $role->id;
        $fields["permission_id"] = ($permission->id == null) ? "" : $permission->id;

        $likeFields = [];

        $relations = $db->findAll("roles_permission",$fields,$page, $likeFields);

        return $relations;
    }
}