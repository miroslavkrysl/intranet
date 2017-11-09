<?php


namespace Core\Contracts\Http;


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
     * Set response header.
     * @param string $header
     * @param string $value
     * @return self
     */
    public function header(string $header, string $value);

    /**
     * Set status code.
     * @param int $status
     * @return self
     */
    public function status(int $status);
}