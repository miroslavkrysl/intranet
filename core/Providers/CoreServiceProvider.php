<?php


namespace Core\Providers;


use Core\Container\ServiceProvider;
use Core\Contracts\Database\DatabaseInterface;
use Core\Database\PDOWrapper;


/**
 * Service provider for application core.
 */
class CoreServiceProvider extends ServiceProvider
{

    /**
     * Inside this method should the services be registered.
     */
    public function register()
    {
        $this->container
            ->register(DatabaseInterface::class, PDOWrapper::class)
            ->addArgument('database.type')
            ->addArgument('database.host')
            ->addArgument('database.dbname')
            ->addArgument('database.username')
            ->addArgument('database.password');
    }
}