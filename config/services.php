<?php


use Core\Config\Config;
use Core\Container\Container;
use Core\Container\ParameterReference as PR;
use Core\Container\ServiceReference as SR;
use Core\Cookies\CookiesManager;
use Core\Database\PDOWrapper;
use Core\Http\Request;
use Core\Session\SessionManager;


/**
 * This file returns a method to be called to register all the container services.
 */

return function (Container $container)
{
    // config - should come first to instant use
    $container->register('config', Config::class)
        ->addCall('loadFromFile', [path('settings')]);

    // request
    $container->register('request', Request::class);

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
    $container->register('cookies', CookiesManager::class);
};