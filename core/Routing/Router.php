<?php


namespace Core\Routing;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\Routing\RouteInterface;
use Core\Contracts\Routing\RouterInterface;

class Router implements RouterInterface
{
    /**
     * Defined routes.
     * @var array
     */
    private $routes;

    /**
     * Dispatch the request.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request): ResponseInterface
    {
        // TODO: Implement dispatch() method.
    }

    /**
     * Register a new route with GET method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function get(string $url, $action): RouteInterface
    {
        // TODO: Implement get() method.
    }

    /**
     * Register a new route with POST method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function post(string $url, $action): RouteInterface
    {
        // TODO: Implement post() method.
    }

    /**
     * Register a new route with PUT method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function put(string $url, $action): RouteInterface
    {
        // TODO: Implement put() method.
    }

    /**
     * Register a new route with DELETE method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function delete(string $url, $action): RouteInterface
    {
        // TODO: Implement delete() method.
    }
}