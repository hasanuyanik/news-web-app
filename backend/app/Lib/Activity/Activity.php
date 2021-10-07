<?php
namespace App\Lib\Activity;

use App\Lib\Database\DatabaseFactory;
use App\Lib\Logger\Logger;

class Activity implements ActivityI
{

    public static function addActivity(mixed $resource_id, string $activity): void
    {
        try {
            $created_at = date('Y.m.d H:i:s');
            $db = (new DatabaseFactory())->db;
            $db->add("activity",
                [
                    "resource_id" => $resource_id,
                    "activity" => $activity,
                    "created_at" => $created_at
                ]
            );
        } catch (\Exception $exception)
        {
            $message = "# Activity: {activity} ".PHP_EOL."# Exception: {exception}";
            $context = [
                "activity" => $activity,
                "exception" => $exception
            ];

            $logger = new Logger();
            $logger->error($message,$context);
        }
    }

    public static function getActivities(int $page, mixed $resource_id = null): array
    {
        try {
            $db = (new DatabaseFactory())->db;
            $fields = ($resource_id == null) ? [] : [ "resource_id" => $resource_id];
            return $db->findAll("activity",
                $fields,
                $page
            );
        } catch (\Exception $exception)
        {
            $message = "# Activity: GetActivities ".PHP_EOL."# Exception: {exception}";
            $context = [
                "exception" => $exception
            ];

            $logger = new Logger();
            $logger->error($message,$context);

            return [];
        }
    }
}
