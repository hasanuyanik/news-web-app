<?php
namespace App\Lib\News;

class NewsRepository
{
    public mixed $id;
    public string $title;
    public string $description;
    public string $content;
    public string $img;
    public int $news_status;
    public string $created_at;
    public string $updated_at;
}