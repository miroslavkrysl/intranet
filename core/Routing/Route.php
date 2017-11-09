<?php


namespace Core\Routing;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\Routing\RouteInterface;
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
     * @var \ReflectionClass|null
     */
    private $controller;

    /**
     * Route controller method.
     * @var \ReflectionMethod|null
     */
    private $controllerMethod;

    /**
     * True if route accepts only ajax requests.
     * @var bool
     */
    private $onlyAjax;

    /**
     * Route constructor.
     * @param string $method
     * @param string $uri
     * @param \Closure|string $action
     */
    public function __construct(string $method, string $uri, $action)
    {
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

        $class = $action[0];
        $method = $action[1];

        if (!\class_exists($class)) {
            throw new RouteActionException('Class ' . $class . ' does not exist.');
        }

        $reflectionClass = new \ReflectionClass($class);

        if (!$reflectionClass->hasMethod($method)) {
            throw new RouteActionException('Controller ' . $class . ' has not the method ' . $method);
        }

        $this->controller = $reflectionClass;
        $this->controllerMethod = $reflectionClass->getMethod($method);
    }

    /**
     * Return true, if the route has a controller action, false when closure action.
     * @return bool
     */
    private function isControllerAction(): bool
    {
        return !\is_null($this->controller);
    }

    /**
     * Make pattern from route uri for matching request uri.
     * @param string $uri
     * @return string
     */
    private function makePattern(string $uri): string
    {
        $pattern = preg_replace('/\//', '\\/', $uri);
        $pattern = preg_replace('/\{([a-z0-9]+)\}/', '(?<${1}>[^#&\/]+)', $pattern);
        $pattern = preg_replace('/\{([a-z0-9]+):([^\}]+)\}/', '(?<${1}>${2})', $pattern);
        $pattern = '/^' . $pattern . '$/i';
        return $pattern;
    }

    /**
     * Check if the route matches given request.
     * @param RequestInterface $request
     * @return null|array
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
        $action = $this->action;
        if (!\is_null($this->controllerMethod)) {
            $controller = $this->controller->newInstance();
            $action = $this->controllerMethod->getClosure($controller);
        }

        preg_match($this->pattern, $request->uri(), $matches);

        $reflectionFunction = new \ReflectionFunction($action);
        $reflectionParameters = $reflectionFunction->getParameters();

        $parameters = [];
        foreach ($reflectionParameters as $rp) {
            $parameters[] = $matches[$rp->getName()];
        }

        return \call_user_func_array($action, $parameters);
    }

    //TODO: middleware
}