<?php


use Core\Contracts\Routing\RouterInterface;


/**
 * This file contains routes registrations to the router.
 */


/** @var RouterInterface $router */
$router = app('router');


// global middleware

$router->middleware('csrf');


/*
|-------------------------------|
|            Routes             |
|-------------------------------|
*/

$router->get('/', 'DashboardController@showDashboard');

// login handling
$router->get('/login', 'LoginController@showLoginForm');
$router->post('/login', 'LoginController@login');
$router->post('/logout', 'LoginController@logout');

$router->get('/user/{username}', 'UserController@index');

$router->get('/zadanky', 'RequestController@index');

