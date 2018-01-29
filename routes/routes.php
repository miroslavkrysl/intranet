<?php


use Core\Contracts\Routing\RouterInterface;


/**
 * This file contains routes registrations to the router.
 */


/** @var RouterInterface $router */
$router = app('router');


// global middleware

$router->middleware('csrf');
//$router->middleware('RestrictToLogged');


/*
|-------------------------------|
|            Routes             |
|-------------------------------|
*/

$router->get('/', 'DashboardController@showDashboard')->middleware('RestrictToLogged');


// login handling
$router->get('/login', 'LoginController@showLoginForm');
$router->post('/login', 'LoginController@login')->jsonOnly();
$router->post('/logout', 'LoginController@logout')->jsonOnly()->middleware('RestrictToLogged');


// user handling
$router->post('/user', 'UserController@create')->jsonOnly()->middleware('RestrictToLogged');
$router->put('/user', 'UserController@update')->jsonOnly()->middleware('RestrictToLogged');
$router->delete('/user', 'UserController@delete')->jsonOnly()->middleware('RestrictToLogged');
$router->get('/users', 'UserController@list')->middleware('RestrictToLogged');
$router->get('/users-table', 'UserController@usersTable')->jsonOnly()->middleware('RestrictToLogged');
$router->get('/user/settings', 'UserController@showSettings')->middleware('RestrictToLogged');
// password reset
$router->get('/user/change-password', 'UserController@showChangePasswordForm');
$router->put('/user/change-password', 'UserController@changePassword')->jsonOnly();
$router->post('/user/send-change-password-email', 'UserController@sendChangePasswordEmail')->jsonOnly();


// request handling
$router->post('/request', 'RequestController@create')->jsonOnly()->middleware('RestrictToLogged');
$router->put('/request', 'RequestController@update')->jsonOnly()->middleware('RestrictToLogged');
$router->delete('/request', 'RequestController@delete')->jsonOnly()->middleware('RestrictToLogged');
$router->get('/requests', 'RequestController@list')->middleware('RestrictToLogged');


// document handling
$router->post('/document', 'DocumentController@create')->jsonOnly()->middleware('RestrictToLogged');
$router->put('/document', 'DocumentController@update')->jsonOnly()->middleware('RestrictToLogged');
$router->delete('/document', 'DocumentController@delete')->jsonOnly()->middleware('RestrictToLogged');
$router->get('/documents', 'DocumentController@list')->middleware('RestrictToLogged');


// car handling
$router->post('/car', 'CarController@create')->jsonOnly()->middleware('RestrictToLogged');
$router->put('/car', 'CarController@update')->jsonOnly()->middleware('RestrictToLogged');
$router->delete('/car', 'CarController@delete')->jsonOnly()->middleware('RestrictToLogged');
$router->get('/cars', 'CarController@list')->middleware('RestrictToLogged');
$router->get('/cars-table', 'CarController@carsTable')->jsonOnly()->middleware('RestrictToLogged');