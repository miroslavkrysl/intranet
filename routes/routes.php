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
$router->post('/login', 'LoginController@login');
$router->post('/logout', 'LoginController@logout')->middleware('RestrictToLogged');


// user handling
$router->post('/user', 'UserController@create')->middleware('RestrictToLogged')->jsonOnly();
$router->put('/user', 'UserController@update')->middleware('RestrictToLogged')->jsonOnly();
$router->delete('/user', 'UserController@delete')->middleware('RestrictToLogged')->jsonOnly();
$router->get('/users', 'UserController@list')->middleware('RestrictToLogged');
$router->get('/user/settings', 'UserController@showSettings')->middleware('RestrictToLogged');
// password reset
$router->get('/user/change-password', 'UserController@showChangePasswordForm');
$router->put('/user/change-password', 'UserController@changePassword')->jsonOnly();
$router->post('/user/send-reset-password-email', 'UserController@sendPasswordResetEmail')->jsonOnly();


// request handling
$router->post('/request', 'RequestController@create')->middleware('RestrictToLogged')->jsonOnly();
$router->put('/request', 'RequestController@update')->middleware('RestrictToLogged')->jsonOnly();
$router->delete('/request', 'RequestController@delete')->middleware('RestrictToLogged')->jsonOnly();
$router->get('/requests', 'RequestController@list')->middleware('RestrictToLogged')->jsonOnly();


// document handling
$router->post('/document', 'DocumentController@create')->middleware('RestrictToLogged')->jsonOnly();
$router->put('/document', 'DocumentController@update')->middleware('RestrictToLogged')->jsonOnly();
$router->delete('/document', 'DocumentController@delete')->middleware('RestrictToLogged')->jsonOnly();
$router->get('/documents', 'DocumentController@list')->middleware('RestrictToLogged');


// car handling
$router->post('/car', 'CarController@create')->middleware('RestrictToLogged')->jsonOnly();
$router->put('/car', 'CarController@update')->middleware('RestrictToLogged')->jsonOnly();
$router->delete('/car', 'CarController@delete')->middleware('RestrictToLogged')->jsonOnly();
$router->get('/cars', 'CarController@list')->middleware('RestrictToLogged');
