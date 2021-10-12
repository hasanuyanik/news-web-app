<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

/* UserController - Start */

$routes->add(
    'user',
    new Route('/api/user/{page}', ['controller' => \App\Controller\UserController::class, 'method' => 'getUsers'], ['page' => '[0-9]+'])
);

$routes->add(
    'user.add',
    new Route('/api/user/add', ['controller' => \App\Controller\UserController::class, 'method' => 'add'])
);

$routes->add(
    'user.edit',
    new Route('/api/user/edit', ['controller' => \App\Controller\UserController::class, 'method' => 'edit'])
);

$routes->add(
    'user.show',
    new Route('/api/user/{name}', ['controller' => \App\Controller\UserController::class, 'method' => 'show'], ['name' => '[A-Za-z0-9]+'])
);

/* UserController - End */


/* AuthController - Start */

$routes->add(
    'user.auth',
    new Route('/api/auth', ['controller' => \App\Controller\AuthController::class, 'method' => 'login'])
);

$routes->add(
    'user.logout',
    new Route('/api/logout', ['controller' => \App\Controller\AuthController::class, 'method' => 'logout'])
);

/* AuthController - End */


/* AccountDeletionController - Start */

$routes->add(
    'userwiper',
    new Route('/api/userwiper/{page}/{status}', ['controller' => \App\Controller\AccountDeletionController::class, 'method' => 'getRequest'], ['page' => '[0-9]+'], ['status' => '[0-9]+'])
);

$routes->add(
    'userwiper.addRequest',
    new Route('/api/userwiper/addRequest', ['controller' => \App\Controller\AccountDeletionController::class, 'method' => 'addRequest'])
);

$routes->add(
    'userwiper.userDelete',
    new Route('/api/userwiper/userdelete', ['controller' => \App\Controller\AccountDeletionController::class, 'method' => 'userDelete'])
);

$routes->add(
    'userwiper.deleteRequest',
    new Route('/api/userwiper/deleteRequest', ['controller' => \App\Controller\AccountDeletionController::class, 'method' => 'deleteRequest'])
);

$routes->add(
    'userwiper.findRequest',
    new Route('/api/userwiper/findRequest', ['controller' => \App\Controller\AccountDeletionController::class, 'method' => 'findRequest'])
);

/* AccountDeletionController - End */


return $routes;