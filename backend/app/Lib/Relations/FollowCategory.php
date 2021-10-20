<?php
namespace App\Lib\Relations;

use App\Lib\Category\Category;
use App\Lib\Category\CategoryRepository;
use App\Lib\Database\DatabaseFactory;
use App\Lib\User\User;
use App\Lib\User\UserRepository;

class FollowCategory
{
    public function getCategory_UserList(int $page, ?CategoryRepository $category, ?UserRepository $user): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($category->id == null) ? [] : ["category_id"=>$category->id];

        $getCategory = (new Category())->getCategories(0,$category);

        if ($category->id == null)
        {
            $fields = ($getCategory[0]["id"]) ? ["category_id"=>$getCategory[0]["id"]] : [];
        }
        $categoryName = ($category->name) ? $category->name : $getCategory[0]["name"];

        $likeFields = [];

        $category_user = $db->findAll("follow_category",$fields,$page, $likeFields);

        $category_userList = [];

        foreach ($category_user as $relation)
        {
            $user->id = $relation["user_id"];
            $getUser = (new User())->getUsers($UserRepository, 0);

            $getUser[0]["id"] = "";
            $getUser[0]["password"] = "";
            array_push($category_userList, $getUser[0]);
        }

        $result = [
            "category" => $getCategory[0],
            "userList" => $category_userList
        ];

        return $result;
    }

    public function getUser_CategoryList(int $page, ?CategoryRepository $category, ?UserRepository $user): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($user->id == null) ? [] : ["user_id"=>$user->id];

        $getUser = (new User())->getUsers($user,0);

        if ($user->id == null)
        {
            $fields = ($getUser[0]["id"]) ? ["user_id"=>$getUser[0]["id"]] : [];
        }

        $likeFields = [];

        $category_user = $db->findAll("follow_category",$fields,$page, $likeFields);

        $user_categoryList = [];

        foreach ($category_user as $relation)
        {
            $CategoryRepository = new CategoryRepository();
            $CategoryRepository->id = $relation["category_id"];
            $getCategory = (new Category())->getCategories(0,$CategoryRepository);

            array_push($user_categoryList, $getCategory[0]);
        }

        $getUser[0]["id"] = "";
        $getUser[0]["password"] = "";

        $result = [
            "user" => $getCategory[0],
            "categoryList" => $user_categoryList
        ];

        return $result;
    }

    public function getRelations(int $page, ?CategoryRepository $category, ?UserRepository $user): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["category_id"] = ($category->id == null) ? "" : $category->id;
        $fields["user_id"] = ($user->id == null) ? "" : $user->id;

        $likeFields = [];

        $relations = $db->findAll("follow_category",$fields,$page, $likeFields);

        return $relations;
    }

    public function add(CategoryRepository $category, UserRepository $user): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new Category())->getCategories(0,$category);
        $getUser = (new User())->getUser($user,0);

        $fields = [];
        $fields["category_id"] = ($category->id) ? $category->id : $getCategory[0]["id"];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];

        $copyRelationControl = $this->getRelations(0,$category, $user);

        if (count($copyRelationControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("follow_category",$fields);

        return $createResult;
    }

    public function delete(CategoryRepository $category, UserRepository $user): string
    {
        $db = (new DatabaseFactory())->db;

        $getCategory = (new Category())->getCategories(0,$category);
        $getUser = (new User())->getUsers($user,0);

        $fields = [];
        $fields["category_id"] = ($category->id) ? $category->id : $getCategory[0]["id"];
        $fields["user_id"] = ($user->id) ? $user->id : $getUser[0]["id"];

        $deleteResult = $db->delete("follow_category", $fields);

        return $deleteResult;
    }
}