<?php


namespace Core\Http;


class ResponseHtml extends Response
{
    /**
     * ResponseHtml constructor.
     */
    public function __construct()
    {
        $this->header("Content-type: text/html");
    }

    /**
     * Send content.
     */
    protected function sendContent()
    {
        echo $this->content();
    }
}