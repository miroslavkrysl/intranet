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
     * @var array
     */
    private $definitions;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $instances;

    /**
     * @var array
     */
    private $building;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        static::$instance = $this;
        $this->instance('container', $this);
    }

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
            $this->instances[$name] = $this->build($this->definitions[$name], $name);
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
        return \array_key_exists($name, $this->definitions)
            or \array_key_exists($name, $this->instances);
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
     * Register a service instance to the container.
     * @param $string
     * @param $this
     */
    public function instance(string $name, $instance)
    {
        $this->instances[$name] = $instance;
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
     * Get definition.
     * @param string $name
     * @return Definition
     * @throws ContainerException
     */
    public function getDefinition(string $name): Definition
    {
        if (!$this->has($name)) {
            throw new ContainerException('Definition of ' . $name . ' not found.');
        }
        return $this->definitions[$name];
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
     * Build the service with it's dependencies using the definition.
     * @param Definition $definition
     * @param string $name
     * @return mixed Service
     * @throws ContainerException When there is a circular service reference.
     */
    private function build(Definition $definition, string $name)
    {
        if (isset($this->building[$name])) {
            throw new ContainerException($name . ' contains circular reference');
        }
        $this->building[$name] = true;

        $arguments = $definition->getArguments();
        $arguments = $this->resolve($arguments);

        $reflectionClass = new \ReflectionClass($definition->getClass());
        $constructor = $reflectionClass->getConstructor();

        if ($constructor and $constructor->getNumberOfRequiredParameters() > count($arguments)) {
            throw new ContainerException('Too few arguments for the ' . $reflectionClass->getName() .
                ' class constructor in service ' . $name);
        }

        $service = $reflectionClass->newInstanceArgs($arguments);

        $calls = $definition->getCalls();

        foreach ($calls as $call) {
            $method = $reflectionClass->getMethod($call['name']);

            if ($constructor->getNumberOfRequiredParameters() > count($arguments)) {
                throw new ContainerException('Too few arguments for the ' . $reflectionClass->getName() .
                    ' class method ' . $method->getName() . ' in service ' . $name);
            }
            $arguments = $this->resolve($call['args']);
            $method->invokeArgs($service, $arguments);
        }

        unset($this->building[$name]);

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
        else if ($value instanceof ServiceReference) {
            $value = $this->get($value->getName());
        }
        else if ($value instanceof ParameterReference) {
            if (!$this->hasParameter($value->getName())) {
                throw new ContainerException('Parameter ' . $value->getName() . ' is not defined.');
            }
            $value = $this->getParameter($value->getName());
        }
        else if ($value instanceof Definition) {
            $value = $this->build($value, null);
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

    /**
     * Get list of definitions.
     * @return array
     */
    public function definitions(): array
    {
        return $this->definitions;
    }

    /**
     * Get list of available services.
     * @return array
     */
    public function services(): array
    {
        return \array_keys($this->definitions);
    }
}
