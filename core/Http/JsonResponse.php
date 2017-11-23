<?php


namespace Core\Http;


use Core\Contracts\View\ViewInterface;

class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     */
    public function __construct(array $data = [])
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        parent::__construct($data, $headers);
    }

    /**
     * Send content.
     */
    protected function sendContent()
    {
        echo \json_encode($this->data());
    }
}