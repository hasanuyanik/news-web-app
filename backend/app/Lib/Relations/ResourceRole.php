<?php
namespace App\Lib\Relations;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Resource\Resource;
use App\Lib\Role\Role;
use App\Lib\Role\RoleRepository;
use App\Lib\User\User;
use App\Lib\User\UserRepository;

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

    public function getRole(int $page, ?Resource $resource, ?Role $role): mixed
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["resource_id"] = ($resource->resource_id == null) ? "" : $resource->resource_id;

        $likeFields["role_id"] = ($role->id == null) ? "" : $role->id;

        $relations = $db->find("resource_roles",$fields);

        $role->id = $relations["role_id"];

        $RoleRepository = new RoleRepository();
        $getRole = $RoleRepository->getRoles(1, $role);

        $role->name = ($getRole["content"][0]["name"]) ? $getRole["content"][0]["name"] : "User";

        return $role;
    }

    public function getRoleUserList(int $page, ?Resource $resource, ?Role $role): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["resource_id"] = ($resource->resource_id == null) ? "" : $resource->resource_id;

        $likeFields["role_id"] = ($role->id == null) ? "" : $role->id;

        $relations = $db->findAll("resource_roles",$fields,$page, $likeFields);

        $pageNumber = $relations["pageNumber"];
        $first = $relations["first"];
        $last = $relations["last"];
        $contents = $relations["content"];

        $RelationAndUserList = [];
        foreach ($contents as $relation)
        {
            $user_id = $relation["resource_id"];
            $User = new User();
            $UserRepository = new UserRepository();
            $User->id = $user_id;
            $GetUser = $UserRepository->findUser($User);

            array_push($RelationAndUserList, [$GetUser]);
        }

        return [
            "content" => $RelationAndUserList,
            "pageNumber" => $pageNumber,
            "first" => $first,
            "last" => $last
        ];
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