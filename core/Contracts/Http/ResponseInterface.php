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
}