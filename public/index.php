<?php
require_once '../vendor/autoload.php';

$routes = new \Symfony\Component\Routing\RouteCollection();

$routes->add(
    'user',
    new \Symfony\Component\Routing\Route('/user',['controller' => User::class, 'method' => 'index'])
);
