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
     * Get request uri.
     * @return string
     */
    public function uri(): string;

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
     * Get the value from GET or the array af all GET inputs.
     * @param string $key
     * @return string|array|null
     */
    public function get($key = null);

    /**
     * Get the value from POST or the array af all POST inputs.
     * @param string $key
     * @return string|array|null
     */
    public function post($key = null);

    /**
     * Get the request uploaded file info, or the array of all files info.
     * @return array|null
     */
    public function file($key = null);

    /**
     * Get the header value or all headers array.
     * @param null $key
     * @return array|string
     */
    public function header($key = null);
}