<?php


namespace Core\Contracts\View;


/**
 * Interface for view factory.
 */
interface ViewFactoryInterface
{
    /**
     * Get the view.
     * @param string $name
     * @return ViewInterface
     */
    public function view(string $name): ViewInterface;
}