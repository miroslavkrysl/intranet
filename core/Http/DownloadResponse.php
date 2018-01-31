<?php


namespace Core\Http;


class DownloadResponse extends Response
{
    /**
     * @var string
     */
    private $filename;

    /**
     * DownloadResponse constructor.
     * @param string $filename
     * @param string $name
     */
    public function __construct(string $filename, string $name = null)
    {
        $this->filename = $filename;

        $mimeType = \mime_content_type($filename);
        $name = $name ?? \basename($filename);

        $headers = [
            'Content-Type' => $mimeType,
            'Content-disposition', 'attachment; filename="' . $name . '"'
        ];

        parent::__construct([], $headers);
    }

    /**
     * Send content.
     */
    protected function sendContent()
    {
        echo \file_get_contents($this->filename);
    }
}