<?php
namespace App\Lib\Relations;

use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\User\User;
use App\Lib\User\UserRepository;

class CategoryUser
{
    public function getCategoryUserList(int $page, ?Category $Category, ?User $User): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($Category->id == null) ? [] : ["category_id"=>$Category->id];

        $getCategory = (new CategoryRepository())->getCategories(0,$Category);

        if ($Category->id == null)
        {
            $fields = ($getCategory[0]["id"]) ? ["category_id"=>$getCategory[0]["id"]] : [];
        }
        $categoryName = ($Category->name) ? $Category->name : $getCategory[0]["name"];

        $likeFields = [];

        $categoryUser = $db->findAll("category_user",$fields,$page, $likeFields);

        $categoryUserList = [];

        foreach ($categoryUser as $relation)
        {
            $User = new User();
            $User->id = $relation["user_id"];
            $getUser = (new UserRepository())->getUsers($User, 0);

            $getUser[0]["id"] = "";
            $getUser[0]["password"] = "";
            array_push($categoryUserList, $getUser[0]);
        }

        $result = [
            "category" => $getCategory[0],
            "userList" => $categoryUserList
        ];

        return $result;
    }

    public function getUserCategoryList(int $page, ?Category $Category, ?User $User): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($User->id == null) ? [] : ["user_id"=>$User->id];

        $getUser = (new UserRepository())->getUsers($User,0);

        if ($User->id == null)
        {
            $fields = ($getUser[0]["id"]) ? ["user_id"=>$getUser[0]["id"]] : [];
        }

        $likeFields = [];

        $categoryUser = $db->findAll("category_user",$fields,$page, $likeFields);

        $userCategoryList = [];

        foreach ($categoryUser as $relation)
        {
            $Category = new Category();
            $Category->id = $relation["category_id"];
            $getCategory = (new CategoryRepository())->getCategories(0,$Category);

            array_push($userCategoryList, $getCategory[0]);
        }

        $getUser[0]["id"] = "";
        $getUser[0]["password"] = "";

        $result = [
            "user" => $getUser[0],
            "categoryList" => $userCategoryList
        ];

        return $result;
    }

    public function getRelations(int $page, ?Category $Category, ?User $User): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["category_id"] = ($Category->id == null) ? "" : $Category->id;
        $fields["user_id"] = ($User->id == null) ? "" : $User->id;

        $likeFields = [];

        $relations = $db->findAll("category_user",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(Category $Category, User $User): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new CategoryRepository())->getCategories(0,$Category);
        $getUser = (new UserRepository())->getUser($User,0);

        $fields = [];
        $fields["category_id"] = ($Category->id) ? $Category->id : $getCategory[0]["id"];
        $fields["user_id"] = ($User->id) ? $User->id : $getUser[0]["id"];

        $copyRelationControl = $this->getRelations(0,$Category, $User);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("category_user",$fields);

        return $createResult;
    }

    public function delete(Category $Category, User $User): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new CategoryRepository())->getCategories(0,$Category);
        $getUser = (new UserRepository())->getUsers($User,0);

        $fields = [];
        $fields["category_id"] = ($Category->id) ? $Category->id : $getCategory[0]["id"];
        $fields["user_id"] = ($User->id) ? $User->id : $getUser[0]["id"];

        $deleteResult = $db->delete("category_user", $fields);

        return $deleteResult;
    }
}