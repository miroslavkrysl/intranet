<?php


use Core\Config\Config;
use Core\Container\Container;
use Core\Cookies\CookiesManager;
use Core\Database\PDOWrapper;
use Core\Session\SessionManager;


/**
 * This file return a method to be called to register all the container services.
 */

return function (Container $container)
{
    // config
    $this->container->register('config', Config::class);

    // database
    $this->container->setParameter('database.type', config('database.type'));
    $this->container->setParameter('database.host', config('database.host'));
    $this->container->setParameter('database.dbname', config('database.dbname'));
    $this->container->setParameter('database.username', config('database.username'));
    $this->container->setParameter('database.password', config('database.password'));

    $this->container->register('database', PDOWrapper::class)
        ->addArgument('database.type')
        ->addArgument('database.host')
        ->addArgument('database.dbname')
        ->addArgument('database.username')
        ->addArgument('database.password');

    // session
    \session_start();
    $this->container->register('session', SessionManager::class);

    // cookies
    $this->container->register('cookies', CookiesManager::class);
};