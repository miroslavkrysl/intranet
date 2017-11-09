<?php


namespace Core\Http;


use Core\Contracts\Http\ResponseInterface;


/**
 * Base response class.
 */
class Response implements ResponseInterface
{
    /**
     * Array of response headers.
     * @var array
     */
    private $headers;

    /**
     * Response content.
     * @var mixed
     */
    private $content;

    /**
     * Response status code.
     * @var int
     */
    private $status;


    public function __construct(string $content = null, array $headers = null, int $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
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
    private function sendContent()
    {
        echo $this->content;
    }

    /**
     * Set response header.
     * @param string $header
     * @param string $value
     * @return self
     */
    public function header(string $header, string $value)
    {
        $this->headers[$header] = $value;
        return $this;
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
}