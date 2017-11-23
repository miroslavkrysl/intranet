<?php


namespace Core\Http;


class RedirectResponse extends Response
{

    /**
     * RedirectResponse constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        $headers = [
            'Location' => $path,
            'Connection' => 'close'
        ];

        parent::__construct(null, $headers);
    }

    /**
     * Send content.
     */
    protected function sendContent()
    {
        return;
    }
}