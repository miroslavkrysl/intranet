<?php

namespace Core\Foundation;

use Core\Container\Container;
use Core\DotArray\DotArray;
use Core\Foundation\Exception\EnvVariableNotExistsException;


/**
 * Main class of the application.
 */
class Application extends Container
{
    /**
     * Basic config files paths relative to the application root directory.
     * @var array
     */
    private $paths = [
        'services' => '/config/services.php',
        'env' => '/.env.json'
    ];

    /**
     * Application root directory absolute path.
     * @var string
     */
    private $rootDir;

    /**
     * Contains environment variables.
     * @var DotArray
     */
    private $env;


    /**
     * Application constructor.
     * @param string $rootDir Application root directory relative path.
     */
    public function __construct(string $rootDir)
    {
        static::$instance = $this;

        $this->rootDir = realpath ($rootDir);
        $this->registerEnvVariables();
        $this->registerServices();
    }

    /**
     * Register services from `services` file.
     */
    private function registerServices()
    {
        $register = require $this->rootDir . $this->paths['services'];
        $register($this);
    }

    /**
     * Get application root directory.
     * @return string
     */
    public function rootDir(): string
    {
        return $this->rootDir;
    }

    /**
     * Register environment variables.
     */
    private function registerEnvVariables()
    {
        $env = \json_decode(\file_get_contents($this->rootDir() . $this->paths['env']), true);
        $this->env = new DotArray($env);
    }

    public function env($key)
    {
        if (!$this->env->has($key)) {
            throw  new EnvVariableNotExistsException('Environment variable ' . $key . ' does not exist.');
        }
        return $this->env->get($key);
    }
}
