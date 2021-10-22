<?php
namespace App\Lib\Relations;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Resource\Resource;
use App\Lib\Role\Role;
use App\Lib\Role\RoleRepository;

class ResourceRole
{

    public function add(Resource $resource, Role $role): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new RoleRepository())->getRoles(0, $role);

        $fields = [];
        $fields["resource_id"] = ($resource->id) ? $resource->id : null;
        $fields["role_id"] = ($role->id) ? $role->id : $getRole[0]["id"];

        $copyRelationControl = $this->getRelations(0,$resource, $role);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("resource_roles",$fields);

        return $createResult;
    }

    public function delete(Resource $resource, Role $role): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new RoleRepository())->getRoles(0, $role);

        $fields = [];
        $fields["role_id"] = ($role->id) ? $role->id : $getRole[0]["id"];
        $fields["resource_id"] = ($resource->id) ? $resource->id : null;

        $deleteResult = $db->delete("resource_roles", $fields);

        return $deleteResult;
    }

    public function getRelations(int $page, ?Resource $resource, ?Role $role): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["resource_id"] = ($resource->resource_id == null) ? "" : $resource->resource_id;

        $likeFields["role_id"] = ($role->id == null) ? "" : $role->id;

        $relations = $db->findAll("resource_roles",$fields,$page, $likeFields);

        return $relations;
    }

}