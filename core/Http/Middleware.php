<?php


namespace Core\Http;


use Core\Contracts\Http\ResponseInterface;


/**
 * Base middleware.
 */
class Middleware
{
    /**
     * The middleware response which can be given.
     * @var ResponseInterface
     */
    private $response;

    /**
     * Get middleware response.
     * @param ResponseInterface|null $response
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
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