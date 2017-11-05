<?php


namespace Core\Http;


/**
 * Class Request represents the http request.
 */
class Request
{

    private $headers;
    private $session;
    private $cookies;

    private $method;
    private $input;
    private $url;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        \session_start();

        $this->headers = \getallheaders();
        $this->session = $_SESSION;

        $this->request = new ParameterBag($request);
        $this->query = new ParameterBag($query);
        $this->attributes = new ParameterBag($attributes);
        $this->cookies = new ParameterBag($cookies);
        $this->files = new FileBag($files);
        $this->server = new ServerBag($server);
        $this->headers = new HeaderBag($this->server->getHeaders());

        $this->content = $content;
        $this->languages = null;
        $this->charsets = null;
        $this->encodings = null;
        $this->acceptableContentTypes = null;
        $this->pathInfo = null;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        $this->method = null;
        $this->format = null;
    }

    public function method()
    {
        return $this->method;
    }

    public function url()
    {
        return $this->url;
    }

    public function all()
    {
        return $this->input;
    }

    private function input(string $key)
    {
        if (!\array_key_exists($key, $this->input)) {
            return null;
        }
        return $this->input($key);
    }

    public function ajax()
    {
        return 'XMLHttpRequest' == $this->header('X-Requested-With');
    }

    public function header(string $key)
    {
        if (!\array_key_exists($key, $this->headers)) {
            return null;
        }
        return $this->input($key);
    }

    public function session(string $key, $value = null)
    {
        if ($value) {

        }
    }

    public function cookie(string $key, $time, $value = null) {

    }
}