<?php


namespace Core\Contracts\Http;


interface RequestInterface
{
    /**
     * Returns true if the request is send by ajax.
     * @return bool
     */
    public function ajax(): bool;

    /**
     * Get the request method.
     * @return string
     */
    public function method(): string;

    /**
     * Get request path.
     * @return string
     */
    public function path(): string;

    /**
     * Get request query string.
     * @return string
     */
    public function query(): string;

    /**
     * Get request fragment.
     * @return string
     */
    public function fragment(): string;

    /**
     * Get the value from $_GET or the $_GET itself.
     * @param string $key
     * @return string|array|null
     */
    public function get($key = null);

    /**
     * Get the value from $_POST or the $_POST itself.
     * @param string $key
     * @return string|array|null
     */
    public function post($key = null);

    /**
     * Get the request uploaded file info, or the $_FILES itself.
     * @return array|null
     */
    public function file($key = null);
}