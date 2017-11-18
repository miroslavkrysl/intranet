<?php


namespace Core\Routing;


use Core\Container\Container;
use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseFactoryInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\Routing\RouteInterface;
use Core\Contracts\Routing\RouterInterface;
use Core\Http\ResponseFactory;
use Core\Routing\Exception\MiddlewareBadReturnTypeException;
use Core\Routing\Exception\MiddlewareNotExistsException;


class Router implements RouterInterface
{
    /**
     * Defined routes.
     * @var array
     */
    private $routes;

    /**
     * Global middleware.
     * @var array
     */
    private $middleware;

    /**
     * @var Container
     */
    private $container;

    /**
     * Router constructor.
     * @param ResponseFactoryInterface $responseFactory
     * @param Route[] $routes
     * @internal param Container $container
     */
    public function __construct(Container $container, array $routes = [])
    {
        $this->routes = $routes;
        $this->middleware = [];
        $this->container = $container;
    }

    /**
     * Dispatch the request.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request): ResponseInterface
    {
        $before = $this->runBeforeMiddleware($request);
        if ($before) {
            return $before;
        }

        $response = null;

        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                $response = $route->run($request);
                break;
            }
        }

        if(!$response) {
            return $this->container->get('response')->error(404);
        }

        $after = $this->runAfterMiddleware($request);
        if ($after) {
            return $after;
        }

        return $response;
    }

    /**
     * Register a new route with GET method and corresponding action.
     * @param string $url
     * @param \Closure|string $action
     * @return RouteInterface
     */
    public function get(string $url, $action): RouteInterface
    {
        $route = new Route('get', $url, $action, $this->container);
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
        $route = new Route('post', $url, $action, $this->container);
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
        $route = new Route('put', $url, $action, $this->container);
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
        $route = new Route('delete', $url, $action, $this->container);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Run middleware before methods. Could return a response.
     * @param RequestInterface $request
     * @return ResponseInterface|null
     */
    private function runBeforeMiddleware(RequestInterface $request)
    {
        return $this->runMiddlewareMethod('before', $request);
    }

    /**
     * Run middleware after methods. Could return a response.
     * @param RequestInterface $request
     * @return ResponseInterface|null
     */
    private function runAfterMiddleware(RequestInterface $request)
    {
        return $this->runMiddlewareMethod('after', $request);
    }

    /**
     * Run a method on the all middleware. Could return a response.
     * @param RequestInterface $request
     * @return ResponseInterface|null
     */
    public function runMiddlewareMethod(string $method, RequestInterface $request)
    {
        foreach ($this->middleware as $middleware) {
            $instance = $this->container->get($middleware['name']);
            $reflectionClass = new \ReflectionClass($instance);

            if (!$reflectionClass->hasMethod($method)) {
                continue;
            }

            $parameters = [$request] + $middleware['parameters'][$method];

            $result = \call_user_func_array(array($instance, $method), $parameters);

            if (!$result) {
                return $instance->getResponse() ?: $this->container->get('response')->whoops();
            }
        }

        return null;
    }

    /**
     * Add a global middleware.
     * @param string $middleware
     * @param array $parameters
     * @return Router
     * @throws MiddlewareNotExistsException
     */
    public function middleware(string $middleware, array $beforeParameters = [], array $afterParameters = [])
    {
        $middleware = 'middleware.' . $middleware;
        if (!$this->container->has($middleware)) {
            throw new MiddlewareNotExistsException('The container has not a middleware ' . $middleware);
        }
        $this->middleware[] = [
            'name' => $middleware,
            'parameters' => [
                'before' => $beforeParameters,
                'after' => $afterParameters
            ]
        ];

        return $this;
    }
}