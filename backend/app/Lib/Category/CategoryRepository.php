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

    public function add(Category $category): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $category->name;
        $fields["url"] = $category->url;

        $copyCategoryControl = $this->getCategories(0, $category);

        if (count($copyCategoryControl) > 0)
        {
            return 0;
        }
        $primaryCopyCategory = new Category();
        $primaryCopyCategory->name = $category->name;
        $primaryCopyCategoryControl = $this->getCategories(0, $primaryCopyCategory);
        if (count($primaryCopyCategoryControl) > 0)
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
        $primaryCopyCategory = new Category();
        $primaryCopyCategory->name = $category->name;
        $primaryCopyCategoryControl = $this->getCategories(0, $primaryCopyCategory);
        if (count($primaryCopyCategoryControl) > 0)
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