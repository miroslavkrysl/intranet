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

$router->get('/', function () {
    var_dump($_SESSION);
    var_dump($_COOKIE);
    echo '
    <form action="/logout" method="post">
        <input type="hidden" name="_token" value="'. csrf_token() .'">
        <input type="submit" name="logout" value="logout">
    </form>
    ';
    var_dump(app('auth')->isLogged());
    return response(view('layouts.app'));
});

// login handling
$router->get('/login', 'LoginController@index');
$router->post('/login', 'LoginController@login');
$router->post('/logout', 'LoginController@logout');

$router->get('/user/{username}', 'UserController@index');

$router->get('/zadanky', 'RequestController@index');

