<?php


namespace Core\Contracts\View;


/**
 * Interface for the view.
 */
interface ViewInterface
{
    /**
     * Render the view with given data.
     * @param array $data
     * @return string
     */
    public function render(array $data = []): string;
}