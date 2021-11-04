<?php
namespace App\Lib\News;

class News
{
    public mixed $id = "";
    public string $title = "";
    public string $url = "";
    public string $description = "";
    public string $content = "";
    public string $img = "";
    public int $news_status = 0;
    public string $created_at;
    public string $updated_at;
}