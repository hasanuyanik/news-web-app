<?php

namespace App\Lib\Activity;

interface ActivityI
{
    public static function addActivity(mixed $resource_id, string $activity): void;
    public static function getActivities(int $page, mixed $resource_id = null): array;
}