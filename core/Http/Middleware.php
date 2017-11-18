<?php


namespace Core\Http;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;


/**
 * Base middleware.
 */
abstract class Middleware
{
    /**
     * The middleware response which can be given.
     * @var ResponseInterface
     */
    private $response;

    /**
     * Middleware before method.
     */
    public function before(RequestInterface $request)
    {

    }

    /**
     * Set middleware response.
     * @param ResponseInterface $response
     */
    protected function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }
}