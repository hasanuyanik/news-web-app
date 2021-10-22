<?php
namespace App\Lib\Relations;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Permission\Permission;
use App\Lib\Resource\Resource;
use App\Lib\Permission\PermissionRepository;

class ResourcePermission
{
    public function add(Resource $resource, Permission $permission): string
    {
        $db = (new DatabaseFactory())->db;

        $getPermission = (new PermissionRepository())->getPermissions(0, $permission);

        $fields = [];
        $fields["resource_id"] = ($resource->id) ? $resource->id : null;
        $fields["permission_id"] = ($permission->id) ? $permission->id : $getPermission[0]["id"];

        $copyRelationControl = $this->getRelations(0,$resource, $permission);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("resource_permission",$fields);

        return $createResult;
    }

    public function delete(Resource $resource, Permission $permission): string
    {

    }

    public function getRelations(int $page, ?Resource $resource, ?Permission $permission): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["resource_id"] = ($resource->resource_id == null) ? "" : $resource->resource_id;
        $fields["permission_id"] = ($permission->id == null) ? "" : $permission->id;

        $likeFields = [];

        $relations = $db->findAll("resource_permission",$fields,$page, $likeFields);

        return $relations;
    }

}