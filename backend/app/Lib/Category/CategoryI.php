<?php
namespace App\Lib\Category;

interface CategoryI
{
    public function getCategories(int $page, ?CategoryRepository $category): array;
    public function add(CategoryRepository $category): string;
    public function edit(CategoryRepository $category): string;
    public function delete(CategoryRepository $category): string;
}