<?php


namespace Core\Contracts\Http;


use Core\Contracts\View\ViewInterface;


/**
 * Http response interface.
 */
interface ResponseInterface
{
    /**
     * Send the response.
     */
    public function send();

    /**
     * Set or get the response header or headers.
     * @param string|array $header
     * @param string $value
     * @return array|mixed|null
     */
    public function header($header = null, string $value = null);

    /**
     * Set status code.
     * @param int $status
     * @return self
     */
    public function status(int $status);

    /**
     * Get or set the response data.
     * @param string $key
     * @param array|mixed $value
     * @return mixed|array|null
     */
    public function data(string $key = null, $value = null);
}