<?php
namespace App\Lib\Category;

use App\Lib\Database\DatabaseFactory;

class CategoryRepository
{
    public function getCategories(int $page, ?Category $category): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($category->id == null) ? [] : ["id"=>$category->id];

        $likeFields = [];
        $likeFields["name"] = $category->name;

        $categories = $db->findAll("category",$fields,$page, $likeFields);

        return $categories;
    }

    public function findCategory(?Category $category): mixed
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = ($category->id == null) ? "" : $category->id;
        $fields["name"] = ($category->name == null) ? "" : $category->name;
        $fields["url"] = ($category->url == null) ? "" : $category->url;

        $category = $db->find("category",$fields);

        return $category;
    }

    public function add(Category $category): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $category->name;
        $fields["url"] = $category->url;

        $copyCategoryControl = $this->findCategory($category);

        if ($copyCategoryControl != false)
        {
            return 0;
        }
        $primaryCopyCategory = new Category();
        $primaryCopyCategory->name = $category->name;
        $primaryCopyCategoryControl = $this->findCategory($primaryCopyCategory);
        if ($primaryCopyCategoryControl != false)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("category",$fields);

        return $createResult;
    }

    public function edit(Category $category): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $category->id;

        $setFields["name"] = $category->name;
        $setFields["url"] = $category->url;
        $primaryCopyCategory = new Category();
        $primaryCopyCategory->name = $category->name;
        $primaryCopyCategory->url = $category->url;
        $primaryCopyCategoryControl = $this->findCategory($primaryCopyCategory);
        if ($primaryCopyCategoryControl != false)
        {
            return 0;
        }

        $editResult = $db->update("category", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(Category $category): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $category->id;

        $deleteResult = $db->delete("category", $fields);

        return $deleteResult;
    }
}