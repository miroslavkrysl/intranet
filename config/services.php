<?php


use Core\Config\Config;
use Core\Container\Container;
use Core\Container\ParameterReference as PR;
use Core\Container\ServiceReference as SR;
use Core\Cookies\CookieManager;
use Core\Database\PDOWrapper;
use Core\Http\Request;
use Core\Session\SessionManager;
use Core\View\TwigView;

/**
 * This file contains services registrations to the container.
 */

$container = app();

// config - should come first to instant use
$container->register('config', Config::class)
    ->addCall('loadFromFile', [path('settings')]);

// request
$container->register('request', Request::class);

// router
$container->register('router', \Core\Routing\Router::class)
    ->addArgument($container);

// responses
$container->register('response', \Core\Http\ResponseFactory::class)
    ->addArgument($container);

// view
$container->register('view', TwigView::class)
    ->addArgument(path('views'));

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