<?php


namespace Core\Contracts\Routing;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;

/**
 * Router interface.
 */
interface RouterInterface
{
    /**
     * Dispatch the request.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request): ResponseInterface;

    /**
     * Register a new route with GET method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function get(string $url, $action): RouteInterface;

    /**
     * Register a new route with POST method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function post(string $url, $action): RouteInterface;

    /**
     * Register a new route with PUT method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function put(string $url, $action): RouteInterface;

    /**
     * Register a new route with DELETE method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function delete(string $url, $action): RouteInterface;
}