<?php


use Core\Contracts\Routing\RouterInterface;


/**
 * This file contains routes registrations to the router.
 */


/** @var RouterInterface $router */
$router = app('router');


// global middleware

$router->middleware('csrf');


// routes

$router->get('/', function () {
    return response(view('layouts.app'));
});

$router->get('/user/{username}', 'UserController@index');

$router->get('/zadanky', 'RequestController@index');