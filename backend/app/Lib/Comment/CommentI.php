<?php
namespace App\Lib\Comment;

interface CommentI
{
    public function getComments(int $page, ?CommentRepository $comment): array;
    public function add(CommentRepository $comment): string;
    public function edit(CommentRepository $comment): string;
    public function delete(CommentRepository $comment): string;
}