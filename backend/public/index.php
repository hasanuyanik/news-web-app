<?php

if (file_exists(__DIR__ . '/maintenance.php')) {
    require __DIR__ . '/maintenance.php';
}

use App\Router;

require __DIR__.'/../vendor/autoload.php';



$routes = require __DIR__.'/../app/routes.php';

(new Router($routes))();


