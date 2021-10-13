<?php
namespace App\Lib\News;

interface NewsI
{
    public function getNews(int $page, ?NewsRepository $news): array;
    public function add(NewsRepository $news): string;
    public function edit(NewsRepository $news): string;
    public function delete(NewsRepository $news): string;
}