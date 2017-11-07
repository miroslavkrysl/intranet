<?php


namespace Core\Http;


class ResponseRedirect extends Response
{
    /**
     * ResponseRedirect constructor.
     * @param string $location
     * @param int|null $code
     */
    public function __construct(string $location)
    {
        $this->header("Location: " . $location);
        $this->header("Connection: close");
    }

    /**
     * Send content.
     */
    protected function sendContent(){}
}