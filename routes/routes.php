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


$router->get('/form', function () {
    return response(view('form', ['title' => 'Form']));
});

$router->post('/form', function () {
    ob_start();
    var_dump($_POST);
    $result = ob_get_clean();
    return response($result);
});