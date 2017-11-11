<?php


use Core\Config\Config;
use Core\Container\Container;
use Core\Container\ParameterReference as PR;
use Core\Container\ServiceReference as SR;
use Core\Cookies\CookieManager;
use Core\Database\PDOWrapper;
use Core\Http\Request;
use Core\Language\Language;
use Core\Session\SessionManager;
use Core\View\TwigView;
use Intranet\Http\Middleware\Csrf;

/**
 * This file contains services registrations to the container.
 */


/** @var Container $container */
$container = app();


/*
|-------------------------------|
|         Core services         |
|-------------------------------|
*/


// config - should come first to instant use
$container->register('config', Config::class)
    ->addCall('loadFromFile', [path('settings')]);


// request
$container->register('request', Request::class)
    ->addCall('createFromGlobals');


// router
$container->register('router', \Core\Routing\Router::class)
    ->addArgument(new SR('container'));


// responses
$container->register('response', \Core\Http\ResponseFactory::class)
    ->addArgument(new SR('container'));


// view
$container->register('view', TwigView::class)
    ->addArgument(new SR('container'))
    ->addArgument(path('views'));


// language
$container->register('language', Language::class)
    ->addArgument(path('language'))
    ->addArgument(\config('app.locale'))
    ->addArgument(\config('app.fallback-locale'));


// database
$container->register('database', PDOWrapper::class)
    ->addArgument(config('database.type'))
    ->addArgument(config('database.host'))
    ->addArgument(config('database.dbname'))
    ->addArgument(config('database.username'))
    ->addArgument(config('database.password'));


// session
$container->register('session', SessionManager::class);


// cookies
$container->register('cookie', CookieManager::class);



/*
|-------------------------------|
|         App services          |
|-------------------------------|
*/


// middleware
$container->register('middleware.csrf', Csrf::class);

// controllers