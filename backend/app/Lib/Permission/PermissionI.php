<?php
namespace App\Lib\Permission;

interface PermissionI
{
    public function getPermissions(int $page, ?PermissionRepository $permissionRepository): array;
    public function add(PermissionRepository $permissionRepository): string;
    public function edit(PermissionRepository $permissionRepository): string;
    public function delete(PermissionRepository $permissionRepository): string;
}