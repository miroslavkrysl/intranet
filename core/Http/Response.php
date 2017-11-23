<?php


namespace Core\Http;


use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\View\ViewInterface;


/**
 * Base response class.
 */
abstract class Response implements ResponseInterface
{
    /**
     * Array of response headers.
     * @var array
     */
    private $headers;

    /**
     * Response data.
     * @var array
     */
    protected $data;

    /**
     * Response status code.
     * @var int
     */
    private $status;

    /**
     * Response constructor.
     * @param array $data
     * @param array|null $headers
     * @param int $status
     */
    public function __construct(array $data = [], array $headers = [], int $status = 200)
    {
        $this->data = $data;
        $this->headers = $headers;
        $this->status = $status;
    }

    /**
     * Send response.
     */
    public function send()
    {
        \http_response_code($this->status);
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Send content.
     */
    protected abstract function sendContent();

    /**
     * Set or get the response header or headers.
     * @param string|array $header
     * @param string $value
     * @return array|mixed|null
     */
    public function header($header = null, string $value = null)
    {
        if (\is_array($header)) {
            $this->headers = \array_merge($this->headers, $header);
        }
        else if (\is_string($header)) {
            if (\is_null($value)) {
                return $this->headers[$header];
            }

            $this->headers[$header] = $value;
        }
        else {
            return $this->headers;
        }
    }

    /**
     * Send headers.
     */
    private function sendHeaders()
    {
        foreach ($this->headers as $header => $value) {
            \header($header . ': ' . $value);
        }
    }

    /**
     * Set status code.
     * @param int $status
     * @return self
     */
    public function status(int $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get or set the response data.
     * @param string $key
     * @param array|mixed $value
     * @return array|mixed|null
     */
    public function data(string $key = null, $value = null)
    {
        if (\is_null($key)) {
            return $this->data;
        }
        if (\is_null($value)) {
            return $this->data[$key];
        }

        $this->data[$key] = $value;
    }
}