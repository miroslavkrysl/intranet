<?php


namespace Core\Container;

/**
 * Represents a reference to an entity with the name.
 */
class Reference
{
    /**
     * Name of the referenced entity.
     * @var string
     */
    protected $name;

    /**
     * Reference constructor.
     * @param string $name Name of the referenced entity
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the name of the referenced entity.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}