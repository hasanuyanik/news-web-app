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

        $getCategory = (new CategoryRepository())->findCategory($Category);

        if ($Category->id == null)
        {
            $fields = ($getCategory["id"]) ? ["category_id"=>$getCategory["id"]] : [];
        }
        $categoryName = ($Category->name) ? $Category->name : $getCategory["name"];

        $likeFields = [];

        $categoryUser = $db->findAll("category_user",$fields,$page, $likeFields);

        $categoryUserList = [];

        foreach ($categoryUser["content"] as $relation)
        {
            $User = new User();
            $User->id = $relation["user_id"];
            $getUser = (new UserRepository())->findUser($User);

            $getUser["id"] = "";
            array_push($categoryUserList, $getUser);
        }

        $result = [
            "category" => $getCategory,
            "content" => $categoryUserList,
            "first" => $categoryUser["first"],
            "last" => $categoryUser["last"],
            "pageNumber" => $categoryUser["pageNumber"]
        ];

        return $result;
    }

    public function getUserCategoryList(int $page, ?Category $Category, ?User $User): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($User->id == null) ? [] : ["user_id"=>$User->id];

        $getUser = (new UserRepository())->findUser($User);

        if ($User->id == null)
        {
            $fields = ($getUser["id"]) ? ["user_id"=>$getUser["id"]] : [];
        }

        $likeFields = [];

        $categoryUser = $db->findAll("category_user",$fields,$page, $likeFields);

        $userCategoryList = [];

        foreach ($categoryUser["content"] as $relation)
        {
            $Category = new Category();
            $Category->id = $relation["category_id"];
            $getCategory = (new CategoryRepository())->findCategory($Category);

            array_push($userCategoryList, $getCategory);
        }

        $getUser["id"] = "";
        $getUser["password"] = "";

        $result = [
            "user" => $getUser,
            "content" => $userCategoryList,
            "first" => $categoryUser["first"],
            "last" => $categoryUser["last"],
            "pageNumber" => $categoryUser["pageNumber"]
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

        $getCategory = (new CategoryRepository())->findCategory($Category);
        $getUser = (new UserRepository())->findUser($User);

        $fields = [];
        $fields["category_id"] = ($Category->id) ? $Category->id : $getCategory[0]["id"];
        $fields["user_id"] = ($User->id) ? $User->id : $getUser[0]["id"];

        $copyRelationControl = $this->getRelations(0,$Category, $User);

        if ($copyRelationControl["content"])
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