<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::index');

$routes->group('auth',function($routes){
    $routes->post('login', 'AuthController::authenticate');
});
