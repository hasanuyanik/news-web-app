<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

//var_dump($_POST);

$routes->add(
    'user',
    new Route('/api/user/{page}', ['controller' => \App\Controller\UserController::class, 'method' => 'getUsers'], ['page' => '[0-9]+'])
);

$routes->add(
    'user.add',
    new Route('/api/user/add', ['controller' => \App\Controller\UserController::class, 'method' => 'add'])
);

$routes->add(
    'user.auth',
    new Route('/api/auth', ['controller' => \App\Controller\UserController::class, 'method' => 'login'])
);

$routes->add(
    'user.logout',
    new Route('/api/logout', ['controller' => \App\Controller\UserController::class, 'method' => 'logout'])
);

$routes->add(
    'user.show',
    new Route('/api/user/{name}', ['controller' => \App\Controller\UserController::class, 'method' => 'show'], ['name' => '[A-Za-z0-9]+'])
);

return $routes;