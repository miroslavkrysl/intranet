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
    private $uri;
    private $query;
    private $fragment;
    private $get;
    private $post;
    private $headers;
    private $files;

    public function createFromGlobals()
    {
        $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        $this->method = \strtolower($_SERVER['REQUEST_METHOD']);

        $this->uri = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_PATH);
        $this->query = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_QUERY);
        $this->fragment = \parse_url($_SERVER['REQUEST_URI'], \PHP_URL_FRAGMENT);
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
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
            return $this->get;
        }
        return isset($this->get[$key]) ? $this->get[$key] : null;
    }

    /**
     * Get the value from POST inputs or the array af all GET inputs.
     * @param string $key
     * @return string|array|null
     */
    public function post($key = null)
    {
        if (\is_null($key)) {
            return $this->post;
        }
        return isset($this->post[$key]) ? $this->post[$key] : null;
    }

    /**
     * Get the request uploaded file info, or the array of all files info.
     * @return array|null
     */
    public function file($key = null)
    {
        if (\is_null($key)) {
            return $this->files;
        }
        return isset($this->files[$key]) ? $this->files[$key] : null;
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
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }
}