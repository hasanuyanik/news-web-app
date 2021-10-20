<?php

if (file_exists(__DIR__ . '/maintenance.php')) {
    require __DIR__ . '/maintenance.php';
}

use App\Router;

require __DIR__.'/../vendor/autoload.php';

set_error_handler(function ($error_level, $error_message, $error_file, $error_line)
{
    $message = "Error : {errorlevel} | Message : {errormessage} | File : {errorfile} | line : {errorline} ";

    $context = [
        "errorlevel" => $error_level,
        "errormessage" => $error_message,
        "errorfile" => $error_file,
        "errorline" => $error_line
    ];

    $Logger = new \App\Lib\Logger\Logger();
    $Logger->error($message, $context);

});

$routes = require __DIR__.'/../app/routes.php';

(new Router($routes))();


