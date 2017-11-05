<?php


namespace Core\Container;


use Core\Container\Exception\DefinitionException;

/**
 * Defines the service with init arguments and method calls.
 */
class Definition
{
    /**
     * Fully classified class name of the service
     * @var string
     */
    private $class;

    /**
     * Arguments of the class constructor.
     * @var mixed[]
     */
    private $arguments;

    /**
     * Methods to be called after the service initialization.
     * @var string[]
     */
    private $calls;

    /**
     * Definition constructor.
     * @param string $class Fully classified class name
     * @param array|null $arguments Names of the arguments for the class constructor
     * @throws DefinitionException When the class does not exist.
     */
    public function __construct(string $class, array $arguments = [])
    {
        if (!\class_exists($class)) {
            throw new DefinitionException('Class ' . $class . ' does not exist.');
        }
        $this->class = $class;
        $this->arguments = $arguments;
    }

    /**
     * Add an argument for the class constructor.
     * @param string $argument Argument name
     * @return self
     */
    public function addArgument(string $argument): self
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * Add a method to be called after the service initialization.
     * @param string $method Method name
     * @param array $arguments Argument names
     * @return self
     * @throws DefinitionException When the method does not exist.
     */
    public function addCall(string $method, array $arguments = array()): self
    {
        if (!\method_exists($this->class, $method)) {
            throw new DefinitionException('Method ' . $this->class . '::' . $method . ' does not exist');
        }
        $this->calls[] = array('name' => $method, 'args' => $arguments);

        return $this;
    }

    /**
     * Get the service class.
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Get the arguments to pass to the service constructor.
     * @return mixed[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Gets the methods to call after the service initialization.
     * @return string[]
     */
    public function getCalls(): array
    {
        return $this->calls;
    }
}