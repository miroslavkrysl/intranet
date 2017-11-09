<?php


namespace Core\Contracts\Routing;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;

interface RouteInterface
{
    /**
     * Get route request method.
     * @return string
     */
    public function method(): string;

    /**
     * Get route uri
     * @return string
     */
    public function uri(): string;

    /**
     * Get route action
     * @return \Closure|string
     */
    public function action();

    /**
     * Set route to accept only ajax requests.
     * @return self
     */
    public function ajaxOnly();

    /**
     * Check if the route accepts only ajax requests.
     * @return bool
     */
    public function isAjaxOnly(): bool;

    /**
     * Check if the route matches given request.
     * @param RequestInterface $request
     * @return bool
     */
    public function matches(RequestInterface $request): bool;

    /**
     * Run the route and return response.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function run(RequestInterface $request): ResponseInterface;

    /**
     * Add a middleware to the route.
     * @param string $middleware
     * @return self
     */
    public function middleware(string $middleware);
}