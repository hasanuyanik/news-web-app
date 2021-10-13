<?php
namespace App\Lib\Role;

interface RoleI
{
    public function getRoles(int $page, ?RoleRepository $roleRepository): array;
    public function add(RoleRepository $roleRepository): string;
    public function edit(RoleRepository $roleRepository): string;
    public function delete(RoleRepository $roleRepository): string;
}