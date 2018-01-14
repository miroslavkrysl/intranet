<?php


use Core\Contracts\Routing\RouterInterface;


/**
 * This file contains routes registrations to the router.
 */


/** @var RouterInterface $router */
$router = app('router');


// global middleware

$router->middleware('csrf');
//$router->middleware('redirectNonLogged');


/*
|-------------------------------|
|            Routes             |
|-------------------------------|
*/

$router->get('/', 'DashboardController@showDashboard')->middleware('redirectNonLogged');

// login handling
$router->get('/login', 'LoginController@showLoginForm');
$router->post('/login', 'LoginController@login');
$router->post('/logout', 'LoginController@logout')->middleware('redirectNonLogged');

// user handling
$router->post('/uzivatel', 'UserController@create')->middleware('redirectNonLogged');
$router->put('/uzivatel', 'UserController@update')->middleware('redirectNonLogged');
$router->delete('/uzivatel', 'UserController@delete')->middleware('redirectNonLogged');
$router->get('/uzivatele', 'UserController@listUsers')->middleware('redirectNonLogged');
$router->get('/uzivatel/nastaveni', 'UserController@showSettings')->middleware('redirectNonLogged');
$router->get('/uzivatel/{username}/password', 'UserController@showChangePasswordForm');
$router->put('/uzivatel/{username}/password', 'UserController@changePassword');
$router->post('/uzivatel/{username}/send-reset-password-email', 'UserController@resetPassword');

// request handling
$router->post('/zadanka', 'RequestController@create')->middleware('redirectNonLogged');
$router->put('/zadanka', 'RequestController@update')->middleware('redirectNonLogged');
$router->delete('/zadanka', 'RequestController@delete')->middleware('redirectNonLogged');
$router->get('/zadanky', 'RequestController@listRequests')->middleware('redirectNonLogged');

// document handing
$router->post('/document', 'DocumentController@create')->middleware('redirectNonLogged');
$router->put('/document', 'DocumentController@update')->middleware('redirectNonLogged');
$router->delete('/document', 'DocumentController@delete')->middleware('redirectNonLogged');
$router->get('/documents', 'DocumentController@listDocuments')->middleware('redirectNonLogged');