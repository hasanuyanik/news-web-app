<?php
namespace App\Lib\Role;

use App\Lib\Database\DatabaseFactory;

class Role
{
    public function getRoles(int $page, ?RoleRepository $roleRepository): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($roleRepository->id == null) ? [] : ["id"=>$roleRepository->id];

        $likeFields = [];
        $likeFields["name"] = $roleRepository->name;

        $roles = $db->findAll("roles",$fields,$page, $likeFields);

        return $roles;
    }

    public function add(RoleRepository $roleRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $roleRepository->name;
        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("roles",$fields);

        return $createResult;
    }

    public function edit(RoleRepository $roleRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $roleRepository->id;

        $setFields["name"] = $roleRepository->name;

        $editResult = $db->update("roles", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(RoleRepository $roleRepository): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $roleRepository->id;

        $deleteResult = $db->delete("roles", $fields);

        return $deleteResult;
    }
}