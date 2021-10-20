<?php
namespace App\Lib\Relations;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Permission\Permission;
use App\Lib\Permission\PermissionRepository;
use App\Lib\Role\Role;
use App\Lib\Role\RoleRepository;

class RolePermission
{
    public function getRole_PermissionList(int $page, ?RoleRepository $roleRepository, ?PermissionRepository $permissionRepository): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($roleRepository->id == null) ? [] : ["role_id"=>$roleRepository->id];

        $getRole = (new Role())->getRoles(0,$roleRepository);

        if ($roleRepository->id == null)
        {
            $fields = ($getRole[0]["id"]) ? ["role_id"=>$getRole[0]["id"]] : [];
        }

        $likeFields = [];

        $roles_permission = $db->findAll("roles_permission",$fields,$page, $likeFields);

        $roles_permissionList = [];

        foreach ($roles_permission as $relation)
        {
            $permissionRepository->id = $relation["permission_id"];
            $getPermission = (new Permission())->getPermissions(0, $permissionRepository);
            array_push($roles_permissionList, $getPermission[0]);
        }

        $result = [
            "role" => $getRole[0],
            "permissionList" => $roles_permissionList
        ];

        return $result;
    }

    public function add(RoleRepository $roleRepository, PermissionRepository $permissionRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new Role())->getRoles(0,$roleRepository);
        $getPermission = (new Permission())->getPermissions(0, $permissionRepository);

        $fields = [];
        $fields["role_id"] = ($roleRepository->id) ? $roleRepository->id : $getRole[0]["id"];
        $fields["permission_id"] = ($permissionRepository->id) ? $permissionRepository->id : $getPermission[0]["id"];

        $copyRelationControl = $this->getRelations(0,$roleRepository, $permissionRepository);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("roles_permission",$fields);

        return $createResult;
    }

    public function delete(RoleRepository $roleRepository, PermissionRepository $permissionRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new Role())->getRoles(0,$roleRepository);
        $getPermission = (new Permission())->getPermissions(0, $permissionRepository);

        $fields = [];
        $fields["role_id"] = ($roleRepository->id) ? $roleRepository->id : $getRole[0]["id"];
        $fields["permission_id"] = ($permissionRepository->id) ? $permissionRepository->id : $getPermission[0]["id"];

        $deleteResult = $db->delete("roles_permission", $fields);

        return $deleteResult;
    }

    public function getRelations(int $page, ?RoleRepository $roleRepository, ?PermissionRepository $permissionRepository): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["role_id"] = ($roleRepository->id == null) ? "" : $roleRepository->id;
        $fields["permission_id"] = ($permissionRepository->id == null) ? "" : $permissionRepository->id;

        $likeFields = [];

        $relations = $db->findAll("roles_permission",$fields,$page, $likeFields);

        return $relations;
    }
}