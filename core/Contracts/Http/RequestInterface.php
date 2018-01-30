<?php


namespace Core\Contracts\Http;


use Core\Contracts\Routing\RouteInterface;

interface RequestInterface
{
    /**
     * Returns true if the request accepts json.
     * @return bool
     */
    public function json(): bool;

    /**
     * Returns true if the request is via ajax.
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
     * Get the value from PUT or the array af all PUT inputs.
     * @param string $key
     * @return string|array|null
     */
    public function put($key = null);

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

    /**
     * Get the route associated with this request.
     * @return RouteInterface|null
     */
    public function route();

    /**
     * Set the route associated with this request.
     * @param RouteInterface $route
     */
    public function setRoute(RouteInterface $route);

    /**
     * Validate request.
     * @param array $rules
     * @return bool
     */
    public function validate(array $rules): bool;

    /**
     * Get validation errors.
     * @return array
     */
    public function errors(): array ;
}