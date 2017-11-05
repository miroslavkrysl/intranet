<?php

namespace Core\Container;


use Core\Container\Exception\ContainerException;


/**
 * Dependency injection container.
 */
class Container
{
    /**
     * Current container instance
     * @var self
     */
    protected static $instance;

    /**
     * @var Definition[]
     */
    private $definitions;

    /**
     * @var mixed[]
     */
    private $parameters;

    /**
     * @var mixed[]
     */
    private $instances;

    /**
     * @var bool[]
     */
    private $building;

    /**
     * Get current Container instance.
     * @return static
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * Get the service.
     * @param string $name
     * @return mixed Service
     * @throws ContainerException If there is not the service definition.
     */
    public function get(string $name)
    {
        if (!$this->has($name)) {
            throw new ContainerException('Service not found: ' . $name);
        }
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $this->build($name);
        }

        return $this->instances[$name];
    }

    /**
     * Check if the container has a registered service.
     * @param string $name
     * @return bool True if service is registered, false otherwise.
     */
    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }

    /**
     * Register a service into the container.
     * @param string $name
     * @param string $class Fully classified class name
     * @return Definition
     */
    public function register(string $name, string $class): Definition
    {
        return $this->setDefinition($name, new Definition($class));
    }

    /**
     * Register a definition into the container.
     * @param string $name
     * @param Definition $definition
     * @return Definition
     */
    public function setDefinition(string $name, Definition $definition): Definition
    {
        return $this->definitions[$name] = $definition;
    }

    /**
     * Register a parameter into the container.
     * @param string $name
     * @param mixed $value
     */
    public function setParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Build the service with it's dependencies.
     * @param string $name
     * @return mixed Service
     * @throws ContainerException When there is a circular service reference.
     */
    private function build(string $name)
    {
        if (isset($this->building[$name])) {
            throw new ContainerException($name . ' contains circular reference');
        }
        $this->building[$name] = true;

        $definition = $this->definitions[$name];
        $arguments = $definition->getArguments();
        $arguments = $this->resolve($arguments);

        $reflectionClass = new \ReflectionClass($name);
        $constructor = $reflectionClass->getConstructor();

        if ($constructor and $constructor->getNumberOfRequiredParameters() > count($arguments)) {
            throw new ContainerException('Too few arguments for the ' . $reflectionClass->getName() .
                ' class constructor in service ' . $name);
        }

        $service = $constructor ? $reflectionClass->newInstance() : $reflectionClass->newInstanceArgs($arguments);

        return $service;
    }

    /**
     * Resolve dependencies.
     * @param mixed|array $value
     * @return mixed
     * @throws ContainerException
     */
    private function resolve($value)
    {
        if (\is_array($value)) {
            foreach ($value as $key => $v) {
                $value[$key] = $this->resolve($v);
            }
        }
        else if ($value instanceof Reference) {
            $value = $this->get($value);
        }
        else if ($value instanceof Definition) {
            $value = $this->build($value);
        }
        else if ($this->hasParameter($value)) {
            $value = $this->getParameter($value);
        }
        else {
            throw new ContainerException('Parameter ' . (string) $value . ' is not defined.');
        }

        return $value;
    }

    /**
     * Checks if the parameter is defined.
     * @param string $name
     * @return bool True if the parameter is defined, false otherwise
     */
    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * Returns parameter value.
     * @param string $name
     * @return mixed
     * @throws ContainerException If parameter is not defined.
     */
    public function getParameter(string $name)
    {
        if (!$this->hasParameter($name)) {
            throw new ContainerException('Parameter ' . $name . ' is not defined');
        }
        return $this->parameters[$name];
    }
}
