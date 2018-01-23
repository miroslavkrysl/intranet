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
$router->put('/user/{username}', 'UserController@update')->jsonOnly()->middleware('RestrictToLogged')->middleware('PasswordAuth');
$router->delete('/user/{username}', 'UserController@delete')->jsonOnly()->middleware('RestrictToLogged');
$router->get('/users', 'UserController@list')->middleware('RestrictToLogged');
$router->get('/user/settings', 'UserController@showSettings')->middleware('RestrictToLogged');
// password reset
$router->get('/user/change-password', 'UserController@showChangePasswordForm');
$router->put('/user/change-password', 'UserController@changePassword')->jsonOnly();
$router->post('/user/send-reset-password-email', 'UserController@sendPasswordResetEmail')->jsonOnly();


// request handling
$router->post('/request', 'RequestController@create')->jsonOnly()->middleware('RestrictToLogged');
$router->put('/request', 'RequestController@update')->jsonOnly()->middleware('RestrictToLogged');
$router->delete('/request', 'RequestController@delete')->jsonOnly()->middleware('RestrictToLogged');
$router->get('/requests', 'RequestController@list')->jsonOnly()->middleware('RestrictToLogged');


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


$router->get('/emailtest', function () {
    $mail = app('mail');
    $ok = $mail->send('mirek.krysl@seznam.cz', 'test', '<h1>Some fancy message</h1>');
    return json(['ok' => $ok]);
});