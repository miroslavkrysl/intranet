<?php


namespace Core\Container;


abstract class ServiceProvider
{
    /**
     * Container where the services will be registered.
     * @var Container
     */
    protected $container;

    /**
     * Inside this method should the services be registered.
     */
    abstract function register();
}