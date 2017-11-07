<?php


namespace Core\Http;


use Core\Contracts\Http\ResponseInterface;


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
     * Response content.
     * @var mixed
     */
    private $content;

    /**
     * Response status code.
     * @var int
     */
    private $code = 200;

    /**
     * Get or set response content.
     * @param mixed|null $content
     * @return mixed
     */
    public function content($content = null)
    {
        if (\is_null($content)){
            return $this->content;
        }
        $this->content = $content;
    }

    /**
     * Set status code.
     * @param int $code
     */
    public function code(int $code = null)
    {
        $this->code = \is_null($code) ? 200 : $code;
    }

    /**
     * Send response.
     */
    public function send()
    {
        \http_response_code($this->code);
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Send content.
     */
    protected abstract function sendContent();

    /**
     * Add response header.
     * @param string $key
     * @param string|null $value
     * @return mixed
     */
    protected function header($header)
    {
        $this->headers[] = $header;
    }

    /**
     * Send headers.
     */
    protected function sendHeaders()
    {
        foreach ($this->headers as $header) {
            \header($header);
        }
    }
}