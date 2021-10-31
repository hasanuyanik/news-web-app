<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();


$routes->add(
    'auth.permission',
    new Route('/api/auth/userpermission', ['controller' => \App\Controller\AuthController::class, 'method' => 'permissionControl'])
);


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

$routes->add(
    'user.sessionControl',
    new Route('/api/sessionControl', ['controller' => \App\Controller\AuthController::class, 'method' => 'SessionControl'])
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



/* CategoryController - Start */

$routes->add(
    'category',
    new Route('/api/category/{page}', ['controller' => \App\Controller\CategoryController::class, 'method' => 'getCategories'], ['page' => '[0-9]+'])
);

$routes->add(
    'category.show',
    new Route('/api/category/show/{categoryUrl}', ['controller' => \App\Controller\CategoryController::class, 'method' => 'show'], ['categoryUrl' => '[A-Za-z0-9_-]+'])
);

$routes->add(
    'category.categories_news',
    new Route('/api/category/news', ['controller' => \App\Controller\CategoryController::class, 'method' => 'getCategories_News'])
);

$routes->add(
    'category.user_assign',
    new Route('/api/category/userassign', ['controller' => \App\Controller\CategoryController::class, 'method' => 'userAssignCategory'])
);

$routes->add(
    'category.add',
    new Route('/api/category/add', ['controller' => \App\Controller\CategoryController::class, 'method' => 'add'])
);

$routes->add(
    'category.edit',
    new Route('/api/category/edit', ['controller' => \App\Controller\CategoryController::class, 'method' => 'edit'])
);

$routes->add(
    'category.delete',
    new Route('/api/category/delete', ['controller' => \App\Controller\CategoryController::class, 'method' => 'delete'])
);

$routes->add(
    'category.assignlist',
    new Route('/api/category/assign/list/{categoryUrl}/{page}', ['controller' => \App\Controller\CategoryController::class, 'method' => 'getCategoryUserList'], ['categoryUrl' => '[A-Za-z0-9_-]+'], ['page' => '[0-9]+'])
);

$routes->add(
    'category.userAssignRelation',
    new Route('/api/category/assign/relation', ['controller' => \App\Controller\CategoryController::class, 'method' => 'getCategoryUserRelation'])
);

$routes->add(
    'category.follow_category',
    new Route('/api/category/follow', ['controller' => \App\Controller\CategoryController::class, 'method' => 'followCategory'])
);

$routes->add(
    'category.user_follow_category',
    new Route('/api/user/follow/category', ['controller' => \App\Controller\CategoryController::class, 'method' => 'getUser_FollowedCategories'])
);

$routes->add(
    'category.category_follow_user',
    new Route('/api/category/follow/user', ['controller' => \App\Controller\CategoryController::class, 'method' => 'getCategories_FollowingUser'])
);

/* CategoryController - End */


/* NewsController - Start */

$routes->add(
    'news',
    new Route('/api/news/{page}', ['controller' => \App\Controller\NewsController::class, 'method' => 'getNews'], ['page' => '[0-9]+'])
);

$routes->add(
    'news.news_comment',
    new Route('/api/news/comment', ['controller' => \App\Controller\NewsController::class, 'method' => 'getNews_Comment'])
);

$routes->add(
    'news.read_user',
    new Route('/api/news/read/user', ['controller' => \App\Controller\NewsController::class, 'method' => 'getNews_ReadUser'])
);

$routes->add(
    'news.read_news',
    new Route('/api/user/read/news', ['controller' => \App\Controller\NewsController::class, 'method' => 'getUser_ReadNews'])
);

$routes->add(
    'news.read',
    new Route('/api/news/read', ['controller' => \App\Controller\NewsController::class, 'method' => 'read_News'])
);

$routes->add(
    'news.add',
    new Route('/api/news/add', ['controller' => \App\Controller\NewsController::class, 'method' => 'add'])
);

$routes->add(
    'news.edit',
    new Route('/api/news/edit', ['controller' => \App\Controller\NewsController::class, 'method' => 'edit'])
);

$routes->add(
    'news.delete',
    new Route('/api/news/delete', ['controller' => \App\Controller\NewsController::class, 'method' => 'delete'])
);

/* NewsController - End */


/* CommentController - Start */

$routes->add(
    'comment',
    new Route('/api/comments/{page}', ['controller' => \App\Controller\CommentController::class, 'method' => 'getComments'], ['page' => '[0-9]+'])
);

$routes->add(
    'comment.user_comment',
    new Route('/api/user/comment', ['controller' => \App\Controller\CommentController::class, 'method' => 'getUser_Comment'])
);

$routes->add(
    'comment.add',
    new Route('/api/comment/add', ['controller' => \App\Controller\CommentController::class, 'method' => 'add'])
);

$routes->add(
    'comment.edit',
    new Route('/api/comment/edit', ['controller' => \App\Controller\CommentController::class, 'method' => 'edit'])
);

$routes->add(
    'comment.delete',
    new Route('/api/comment/delete', ['controller' => \App\Controller\CommentController::class, 'method' => 'delete'])
);

/* CommentController - End */

return $routes;