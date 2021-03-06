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
     * Set route to accept only json requests.
     * @return self
     */
    public function jsonOnly();

    /**
     * Check if the route accepts only ajax requests.
     * @return bool
     */
    public function isJsonOnly(): bool;

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
     * Get the parameter.
     * @param string $name
     * @return mixed
     */
    public function parameter(string $name);

    /**
     * Add a middleware to the route.
     * @param string $middleware
     * @param array $beforeParameters
     * @param array $afterParameters
     * @return RouteInterface
     */
    public function middleware(string $middleware, array $beforeParameters = [], array $afterParameters = []);
}