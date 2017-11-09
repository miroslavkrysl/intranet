<?php


namespace Core\Routing;


use Core\Container\Container;
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
     * Global container instance.
     * @var Container
     */
    private $container;

    /**
     * Router constructor.
     * @param Container $container
     * @param Route[] $routes
     */
    public function __construct(Container $container, array $routes = [])
    {
        $this->container = $container;
        $this->routes = $routes;
    }

    /**
     * Dispatch the request.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request): ResponseInterface
    {
        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                return $route->run($request);
            }
        }

        return error(404);
    }

    /**
     * Register a new route with GET method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function get(string $url, $action): RouteInterface
    {
        $route = new Route('get', $url, $action);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Register a new route with POST method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function post(string $url, $action): RouteInterface
    {
        $route = new Route('post', $url, $action);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Register a new route with PUT method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function put(string $url, $action): RouteInterface
    {
        $route = new Route('put', $url, $action);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Register a new route with DELETE method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function delete(string $url, $action): RouteInterface
    {
        $route = new Route('delete', $url, $action);
        $this->routes[] = $route;
        return $route;
    }
}