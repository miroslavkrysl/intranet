<?php


use Core\Config\Config;
use Core\Container\Container;
use Core\Cookies\CookiesManager;
use Core\Database\PDOWrapper;
use Core\Session\SessionManager;


/**
 * This file returns a method to be called to register all the container services.
 */

return function (Container $container)
{
    // config
    $container->setParameter('config.path', path('settings'));

    $container->register('config', Config::class)
        ->addCall('loadFromFile', ['config.path']);

    // database
    $container->setParameter('database.type', config('database.type'));
    $container->setParameter('database.host', config('database.host'));
    $container->setParameter('database.dbname', config('database.dbname'));
    $container->setParameter('database.username', config('database.username'));
    $container->setParameter('database.password', config('database.password'));

    $container->register('database', PDOWrapper::class)
        ->addArgument('database.type')
        ->addArgument('database.host')
        ->addArgument('database.dbname')
        ->addArgument('database.username')
        ->addArgument('database.password');

    // session
    $container->register('session', SessionManager::class);

    // cookies
    $container->register('cookies', CookiesManager::class);
};