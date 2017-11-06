<?php


namespace Core\Http;


use Core\Contracts\Http\RequestInterface;


/**
 * Class Request represents the http request.
 */
class Request implements RequestInterface
{
    private $ajax;
    private $method;
    private $path;
    private $query;
    private $fragment;
    private $get;
    private $post;

    public function __construct()
    {
        $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        $this->method = \strtolower($_SERVER['REQUEST_METHOD']);

        $this->path = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH);
        $this->query = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_QUERY);
        $this->fragment = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_FRAGMENT);
        $this->get = $_GET;
        $this->post = $_POST;
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
     * Get request path.
     * @return string
     */
    public function path(): string
    {
        return $this->path;
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
     * Get the value from $_GET or the $_GET itself.
     * @param string $key
     * @return string|array|null
     */
    public function get($key = null)
    {
        if (\is_null($key)) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    /**
     * Get the value from $_POST or the $_POST itself.
     * @param string $key
     * @return string|array|null
     */
    public function post($key = null)
    {
        if (\is_null($key)) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    /**
     * Get the request uploaded file info, or the $_FILES itself.
     * @return array|null
     */
    public function file($key = null)
    {
        if (\is_null($key)) {
            return $_FILES;
        }
        return isset($_FILES[$key]) ? $_FILES[$key] : null;
    }
}