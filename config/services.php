<?php


use Core\Config\Config;
use Core\Container\Container;
use Core\Container\ParameterReference as PR;
use Core\Cookies\CookiesManager;
use Core\Database\PDOWrapper;
use Core\Session\SessionManager;


/**
 * This file returns a method to be called to register all the container services.
 */

return function (Container $container)
{
    // config
    $container->register('config', Config::class)
        ->addCall('loadFromFile', [path('settings')]);

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