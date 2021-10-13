<?php
namespace App\Lib\Relations;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Resource\ResourceRepository;
use App\Lib\Role\Role;
use App\Lib\Role\RoleRepository;

class Resource_Role
{

    public function add(ResourceRepository $resourceRepository, RoleRepository $roleRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new Role())->getRoles(0, $roleRepository);

        $fields = [];
        $fields["resource_id"] = ($resourceRepository->id) ? $resourceRepository->id : null;
        $fields["role_id"] = ($roleRepository->id) ? $roleRepository->id : $getRole[0]["id"];

        $copyRelationControl = $this->getRelations(0,$resourceRepository, $roleRepository);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("resource_roles",$fields);

        return $createResult;
    }

    public function delete(ResourceRepository $resourceRepository, RoleRepository $roleRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $getRole = (new Role())->getRoles(0, $roleRepository);

        $fields = [];
        $fields["role_id"] = ($roleRepository->id) ? $roleRepository->id : $getRole[0]["id"];
        $fields["resource_id"] = ($resourceRepository->id) ? $resourceRepository->id : null;

        $deleteResult = $db->delete("resource_roles", $fields);

        return $deleteResult;
    }

    public function getRelations(int $page, ?ResourceRepository $resourceRepository, ?RoleRepository $roleRepository): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["resource_id"] = ($resourceRepository->id == null) ? "" : $resourceRepository->id;
        $fields["role_id"] = ($roleRepository->id == null) ? "" : $roleRepository->id;

        $likeFields = [];

        $relations = $db->findAll("resource_roles",$fields,$page, $likeFields);

        return $relations;
    }

}