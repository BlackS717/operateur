<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::index');

$routes->group('auth', function ($routes) {
    $routes->post('login', 'AuthController::authenticate');
    $routes->get('logout', 'AuthController::logout');
});

$routes->group('user', ['filter' => 'auth:client'], function ($routes) {
    $routes->get('/', 'ClientController::index');
    $routes->get('depot', 'ClientController::depot');
    $routes->post('depot', 'ClientController::depotSubmit');
    $routes->get('retrait', 'ClientController::retrait');
    $routes->post('retrait', 'ClientController::retraitSubmit');
    $routes->get('transfert', 'ClientController::transfert');
    $routes->post('transfert', 'ClientController::transfertSubmit');
    $routes->get('historique', 'ClientController::historique');
});

$routes->get('admin/login', 'OperateurAuthController::index');
$routes->post('admin/login', 'OperateurAuthController::authenticate');
$routes->get('admin/logout', 'OperateurAuthController::logout');

$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'OperateurController::index');
    $routes->get('prefixes', 'OperateurController::prefixes');
    $routes->post('prefixes', 'OperateurController::prefixesAdd');
    $routes->get('prefixes/delete/(:num)', 'OperateurController::prefixesDelete/$1');
    $routes->get('types', 'OperateurController::types');
    $routes->post('types', 'OperateurController::typesAdd');
    $routes->get('frais', 'OperateurController::frais');
    $routes->post('frais', 'OperateurController::fraisAdd');
    $routes->get('frais/edit/(:num)', 'OperateurController::fraisEdit/$1');
    $routes->post('frais/edit/(:num)', 'OperateurController::fraisEditSubmit/$1');
    $routes->get('frais/delete/(:num)', 'OperateurController::fraisDelete/$1');
    $routes->get('gains', 'OperateurController::gains');
    $routes->get('clients', 'OperateurController::clients');
});
