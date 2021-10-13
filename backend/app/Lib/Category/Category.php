<?php
namespace App\Lib\Category;

use App\Lib\Database\DatabaseFactory;

class Category implements CategoryI
{

    public function getCategories(int $page, ?CategoryRepository $category): array
    {
        $db = (new DatabaseFactory())->db;

        $fields = ($category->id == null) ? [] : ["id"=>$category->id];

        $likeFields = [];
        $likeFields["name"] = $category->name;

        $categories = $db->findAll("category",$fields,$page, $likeFields);

        return $categories;
    }

    public function add(CategoryRepository $category): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["name"] = $category->name;

        $copyCategoryControl = $this->getCategories($category);

        if (count($copyCategoryControl) > 0)
        {
            return 0;
        }
        $primaryCopyCategory = new CategoryRepository();
        $primaryCopyCategory->name = $category->name;
        $primaryCopyCategoryControl = $this->getCategories($primaryCopyCategory);
        if (count($primaryCopyCategoryControl) > 0)
        {
            return 0;
        }

        $fields["created_at"] = date('Y.m.d H:i:s');

        $createResult = $db->add("category",$fields);

        return $createResult;
    }

    public function edit(CategoryRepository $category): string
    {
        $db = (new DatabaseFactory())->db;

        $whereFields = [];
        $whereFields["id"] = $category->id;

        $setFields["name"] = $category->name;
        $primaryCopyCategory = new CategoryRepository();
        $primaryCopyCategory->name = $category->name;
        $primaryCopyCategoryControl = $this->getCategories($primaryCopyCategory);
        if (count($primaryCopyCategoryControl) > 0)
        {
            return 0;
        }

        $editResult = $db->update("category", $setFields, $whereFields);

        return $editResult;
    }

    public function delete(CategoryRepository $category): string
    {
        $db = (new DatabaseFactory())->db;

        $fields = [];
        $fields["id"] = $category->id;

        $deleteResult = $db->delete("category", $fields);

        return $deleteResult;
    }
}