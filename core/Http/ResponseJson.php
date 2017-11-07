<?php


namespace Core\Http;


class ResponseJson extends Response
{
    /**
     * ResponseJson constructor.
     */
    public function __construct()
    {
        $this->header("Content-type: application/json");
    }

    /**
     * Send content.
     */
    protected function sendContent()
    {
        echo \json_encode($this->content());
    }
}