<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add(
    'user',
    new Route('/api/user', ['controller' => \App\Controller\UserController::class, 'method' => 'index'])
);

$routes->add(
    'user.show',
    new Route('/api/user/{name}', ['controller' => \App\Controller\UserController::class, 'method' => 'show'], ['name' => '[A-Za-z0-9]+'])
);

return $routes;