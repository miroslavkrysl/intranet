<?php


namespace Core\Routing;


use Core\Container\Container;
use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\Routing\RouteInterface;
use Core\Routing\Exception\MiddlewareNotExistsException;
use Core\Routing\Exception\RouteActionException;


class Route implements RouteInterface
{
    /**
     * Route request method.
     * @var string
     */
    private $method;

    /**
     * Route uri.
     * @var string
     */
    private $uri;

    /**
     * Uri pattern.
     * @var string
     */
    private $pattern;

    /**
     * Route action.
     * @var \Closure|string
     */
    private $action;

    /**
     * Route controller.
     * @var string|null
     */
    private $controller;

    /**
     * Route controller method.
     * @var string|null
     */
    private $controllerMethod;

    /**
     * True if route accepts only ajax requests.
     * @var bool
     */
    private $onlyAjax;

    /**
     * Route middleware.
     * @var array
     */
    private $middleware;

    /**
     * Global container instance.
     * @var Container
     */
    private $container;

    /**
     * Associative array of the route parameters.
     * @var array
     */
    private $parameters;

    /**
     * Route constructor.
     * @param string $method
     * @param string $uri
     * @param \Closure|string $action
     */
    public function __construct(string $method, string $uri, $action, Container $container)
    {
        $this->container = $container;
        $this->middleware = [];
        $this->method = $method;
        $this->uri = $uri;
        $this->pattern = $this->makePattern($uri);
        $this->action = $action;
        $this->parseAction($action);
        $this->onlyAjax = false;
    }

    /**
     * Get route request method.
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get route uri.
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get route action
     * @return mixed
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * Parse action
     * @param $action
     * @throws RouteActionException
     */
    private function parseAction($action)
    {
        if ($action instanceof \Closure) {
            return;
        }
        if (!\is_string($action)) {
            throw new RouteActionException('Route action can be only a Closure' .
                ' or a string with controller name and method name delimited with `@`.');
        }

        $action = \explode('@', $action);

        if (\count($action) != 2) {
            throw new RouteActionException('Controller route action must contain a controller name and a method name.');
        }

        $this->controller = $action[0];
        $this->controllerMethod = $action[1];
    }

    /**
     * Make pattern from route uri for matching request uri.
     * @param string $uri
     * @return string
     */
    private function makePattern(string $uri): string
    {
        $pattern = preg_replace('/\//', '\\/', $uri);
        $pattern = preg_replace('/\{([a-z0-9_]+)\}/', '(?<${1}>[^#&\/]+)', $pattern);
        $pattern = preg_replace('/\{([a-z0-9_]+):([^\}]+)\}/', '(?<${1}>${2})', $pattern);
        $pattern = '/^' . $pattern . '$/i';
        return $pattern;
    }

    /**
     * Check if the route matches given request.
     * @param RequestInterface $request
     * @return bool
     */
    public function matches(RequestInterface $request): bool
    {
        return preg_match($this->pattern, $request->uri())
            && $this->method() == $request->method()
            && ($this->isAjaxOnly() ? $request->ajax() : true);
    }

    /**
     * Set route to accept only ajax requests.
     * @return self
     */
    public function ajaxOnly()
    {
        $this->onlyAjax = true;
        return $this;
    }

    /**
     * Check if the route accepts only ajax requests.
     * @return bool
     */
    public function isAjaxOnly(): bool
    {
        return $this->onlyAjax;
    }

    /**
     * Run the route and return response.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        $request->setRoute($this);
        $this->setParameters($request);

        $before = $this->runBeforeMiddleware($request);
        if ($before) {
            return $before;
        }

        $response = $this->runAction($request);

        $after = $this->runAfterMiddleware($request);
        if ($after) {
            return $after;
        }

        return $response;
    }

    /**
     * Run the route action and return response.
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function runAction(RequestInterface $request): ResponseInterface
    {

        if ($this->controller) {
            if (!$this->container->has($this->controller)) {
                throw new RouteActionException(\sprintf('Controller service %s does not exist.', $this->controller));
            }

            $controller = $this->container->get($this->controller);
            $reflectionClass = new \ReflectionClass($controller);

            if (!$reflectionClass->hasMethod($this->controllerMethod)) {
                throw new RouteActionException(\sprintf('Controller service %s has not the method %s.',
                    $this->controller,
                    $this->controllerMethod));
            }

            return \call_user_func_array([$controller, $this->controllerMethod], [$request]);
        }

        return \call_user_func_array($this->action, [$request]);
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

            if ($result) {
                return $result;
            }
        }

        return null;
    }

    /**
     * Add a middleware to the route.
     * @param string $middleware
     * @param array $parameters
     * @return Route
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

    /**
     * Get the parameter.
     * @param string $name
     * @return mixed
     */
    public function parameter(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * Set the route parameters from uri matches.
     * @param RequestInterface $request
     */
    private function setParameters(RequestInterface $request)
    {
        preg_match($this->pattern, $request->uri(), $matches);

        foreach ($matches as $key => $value) {
            if (\is_string($key)) {
                $this->parameters[$key] = $value;
            }
        }
    }
}