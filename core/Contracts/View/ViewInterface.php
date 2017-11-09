<?php


namespace Core\Contracts\View;


/**
 * Interface for view maker.
 */
interface ViewInterface
{
    /**
     * Find the specified view, push the data to the view and return result.
     * @param string $name
     * @param array $data
     * @return string
     */
    public function render(string $name, array $data = []): string;
}