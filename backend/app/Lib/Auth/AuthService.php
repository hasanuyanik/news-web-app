<?php
namespace App\Lib\Auth;

use App\Controller\UserController;
use App\Lib\Auth\Token\Token;
use App\Lib\Auth\Token\TokenRepository;
use App\Lib\Encoder\Encoder;
use App\Lib\Permission\Permission;
use App\Lib\Relations\ResourcePermission;
use App\Lib\Relations\ResourceRole;
use App\Lib\Relations\RolePermission;
use App\Lib\Resource\Resource;
use App\Lib\Role\Role;
use App\Lib\Role\RoleRepository;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserVM;

class AuthService
{

    public function logout(UserVM $user): string
    {
        $token = new Token();
        $token->token = $user->token;

        $tokenRepository = new TokenRepository();
        return $tokenRepository->delete($token);
    }

    public function login(UserVM $user): void
    {
        $token = new Token();
        $token->resource_type = "user";
        $token->token = $user->token;
        $token->created_at = date('Y.m.d H:i:s');

        $encoder = new Encoder();
        $password = $encoder->salt($encoder->encode($user->password));

        $User = new User();
        $User->username = $user->username;
        $User->password = $password;

        $UserRepository = new UserRepository();
        $UserControl = $UserRepository->findUser($User);

        if (count($UserControl) > 0)
        {
            $token->resource_id = $UserControl["id"];

            $tokenRepository = new TokenRepository();
            $newToken = $tokenRepository->create($token);

            $UserControl["token"] = $newToken;
            $ResourceRole = new ResourceRole();
            $Resource = new Resource();
            $Role = new Role();
            $Resource->resource_id = $UserControl["id"];
            $getRole = $ResourceRole->getRole(1, $Resource, $Role);

            $UserControl["role"] = $getRole->name;

            header('Content-Type: application/json; charset=utf-8', response_code: 201);

            echo json_encode($UserControl);

            return;
        }

        $UserController = new UserController();

        echo json_encode($UserController->errors);
    }

    public function UserPermissionControl(User $user, Permission $permission): int
    {
        $Resource = new Resource();
        $Resource->resource_type = "user";
        $Resource->resource_id = $user->id;

        $UserPermission = new ResourcePermission();
        $UserPermissionRelation = $UserPermission->getRelations(0, $Resource, $permission);

        if ($UserPermissionRelation)
        {
            return 1;
        }

        $Role = new Role();
        $ResourceRole = new ResourceRole();
        $ResourceRoleRelation = $ResourceRole->getRelations(0, $Resource, $Role);
        $Role->id = $ResourceRoleRelation[0]["role_id"];

        $RolePermission = new RolePermission();
        $RolePermissionRelation = $RolePermission->getRelations(0, $Role, $permission);

        return ($RolePermissionRelation) ? 1 : 0;
    }

}