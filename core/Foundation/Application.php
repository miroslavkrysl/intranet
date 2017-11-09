<?php

namespace Core\Foundation;

use Core\Container\Container;
use Core\Contracts\Http\RequestInterface;
use Core\DotArray\DotArray;
use Core\Foundation\Exception\EnvVariableNotExistsException;
use Core\Foundation\Exception\PathNotExistsException;


/**
 * Main class of the application.
 */
class Application extends Container
{
    /**
     * Basic config files paths relative to the application root directory.
     * @var array
     */
    private $paths;

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
        $this->registerPaths();
        $this->registerEnvVariables();
        $this->registerServices();
    }

    public function handle(RequestInterface $request)
    {

    }

    /**
     * Register application paths.
     */
    private function registerPaths()
    {
        $this->paths = require($this->rootDir() . '/config/paths.php');
    }

    /**
     * Register environment variables.
     */
    private function registerEnvVariables()
    {
        $env = \json_decode(\file_get_contents($this->path('env')), true);
        $this->env = new DotArray($env);
    }

    /**
     * Register services from `services` file.
     */
    private function registerServices()
    {
        require($this->path('services'));
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
     * Get path from defined paths.
     * @return string
     */
    public function path($name): string
    {
        if (!isset($this->paths[$name])) {
            throw new PathNotExistsException('Path with name ' . $name . ' does not exist.');
        }
        return $this->rootDir() . $this->paths[$name];
    }
    public function env($key)
    {
        if (!$this->env->has($key)) {
            throw  new EnvVariableNotExistsException('Environment variable ' . $key . ' does not exist.');
        }
        return $this->env->get($key);
    }
}
