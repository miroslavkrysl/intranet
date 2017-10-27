<?php

namespace Core\Container;

/**
 * Dependency injection container.
 */
class Container
{
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
     * Get the service.
     * @param string $name
     * @return mixed Service
     */
    public function get(string $name): mixed
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
    public function setParameter(string $name, mixed $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Build the service with it's dependencies.
     * @param string $name
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

        foreach ($arguments as $argument) {
            if (!isset($this->parameters[$argument])) {
                throw new ContainerException('Parameter ' . $argument .
                    ' for service ' . $name . ' is not defined');
            }

        }
    }
}
