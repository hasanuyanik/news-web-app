<?php
namespace App\Lib\Relations;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Permission\Permission;
use App\Lib\Resource\ResourceRepository;
use App\Lib\Permission\PermissionRepository;

class Resource_Permission
{
    public function add(ResourceRepository $resourceRepository, PermissionRepository $permissionRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $getPermission = (new Permission())->getPermissions(0, $permissionRepository);

        $fields = [];
        $fields["resource_id"] = ($resourceRepository->id) ? $resourceRepository->id : null;
        $fields["permission_id"] = ($permissionRepository->id) ? $permissionRepository->id : $getPermission[0]["id"];

        $copyRelationControl = $this->getRelations(0,$resourceRepository, $permissionRepository);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("resource_permission",$fields);

        return $createResult;
    }

    public function delete(ResourceRepository $resourceRepository, PermissionRepository $permissionRepository): string
    {

    }

    public function getRelations(int $page, ?ResourceRepository $resourceRepository, ?PermissionRepository $permissionRepository): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["resource_id"] = ($resourceRepository->id == null) ? "" : $resourceRepository->id;
        $fields["permission_id"] = ($permissionRepository->id == null) ? "" : $permissionRepository->id;

        $likeFields = [];

        $relations = $db->findAll("resource_permission",$fields,$page, $likeFields);

        return $relations;
    }

}