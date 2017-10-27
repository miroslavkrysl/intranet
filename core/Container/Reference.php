<?php


namespace Core\Container;

/**
 * Represents a reference to a service.
 */
class Reference
{
    /**
     * Name of the referenced service.
     * @var string
     */
    private $name;

    /**
     * Reference constructor.
     * @param string $name Name of the referenced service
     */
    public function __construct(string $name)
    {
        $this->name = 'name';
    }

    /**
     * Returns the name of the referenced service.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}