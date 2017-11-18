<?php


namespace Core\Http;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Routing\RouteInterface;
use Core\DotArray\DotArray;
use Core\Validation\Validator;


/**
 * Class Request represents the http request.
 */
class Request implements RequestInterface
{
    private $ajax;
    private $method;
    private $uri;
    private $query;
    private $fragment;

    /**
     * @var DotArray
     */
    private $get;

    /**
     * @var DotArray
     */
    private $post;

    /**
     * @var DotArray
     */
    private $files;

    /**
     * @var array
     */
    private $parameters;

    private $headers;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Route associated with this request.
     * @var RouteInterface
     */
    private $route;

    /**
     * Request constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Create request from php globals $_SERVER, $_GET, $_POST and $_FILES.
     */
    public function createFromGlobals()
    {
        $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        $this->method = \strtolower($_SERVER['REQUEST_METHOD']);

        $this->uri = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH);
        $this->query = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_QUERY);
        $this->fragment = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_FRAGMENT);
        $this->get = new DotArray($_GET);
        $this->post = new DotArray($_POST);
        $this->files = new DotArray($_FILES);
        $this->headers = \getallheaders();
    }

    /**
     * Returns true if the request is send by ajax.
     * @return bool
     */
    public function ajax(): bool
    {
        return $this->ajax;
    }

    /**
     * Get the request method.
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get request uri.
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get request query string.
     * @return string
     */
    public function query(): string
    {
        return $this->query;
    }

    /**
     * Get request fragment.
     * @return string
     */
    public function fragment(): string
    {
        return $this->fragment;
    }

    /**
     * Get the value from GET inputs or the array af all GET inputs.
     * @param string $key
     * @return string|array|null
     */
    public function get($key = null)
    {
        if (\is_null($key)) {
            return $this->get->get();
        }
        return $this->get->has($key) ? $this->get->get('') : null;
    }

    /**
     * Get the value from POST inputs or the array af all GET inputs.
     * @param string $key
     * @return string|array|null
     */
    public function post($key = null)
    {
        if (\is_null($key)) {
            return $this->post->get();
        }
        return $this->post->has($key) ? $this->post->get($key) : null;
    }

    /**
     * Get the request uploaded file info, or the array of all files info.
     * @return array|null
     */
    public function file($key = null)
    {
        if (\is_null($key)) {
            return $this->files->get();
        }
        return $this->files->has($key) ? $this->files->get($key) : null;
    }

    /**
     * Get the header value or all headers array.
     * @param null $key
     * @return array|string
     */
    public function header($key = null)
    {
        if (\is_null($key)) {
            return $this->headers;
        }
        return $this->headers[$key] ?? null;
    }

    /**
     * Get the route associated with this request.
     * @return RouteInterface|null
     */
    public function route()
    {
        return $this->route;
    }

    /**
     * Set the route associated with this request.
     * @param RouteInterface $route
     */
    public function setRoute(RouteInterface $route)
    {
        $this->route = $route;
    }

    /**
     * Getter magic method for input parameters.
     * @param $name
     * @return array|null|string
     */
    public function __get($name)
    {
        return $this->post($name) ?? $this->route()->parameter($name);
    }
}