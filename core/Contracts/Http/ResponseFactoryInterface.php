<?php


namespace Core\Contracts\Http;


interface ResponseFactoryInterface
{
    /**
     * Create a new html response.
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function html(string $content, int $status = 200, array $headers = []): ResponseInterface;

    /**
     * Create a new json response.
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return ResponseInterface
     */
    public function json(array $data = [], int $status = 200, array $headers = [], $options = 0): ResponseInterface;

    /**
     * Create a new file download response.
     * @param string $filename
     * @param string $name
     * @param array $headers
     * @param string $disposition
     * @return ResponseInterface
     */
    public function download(string $filename, string $name = null, array $headers = [], $disposition = 'attachment'): ResponseInterface;

    /**
     * Create a new redirection response.
     * @param string $path
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    public function redirect(string $path, int $status = 302, array $headers = []): ResponseInterface;

    /**
     * Create a new error response.
     * @param int $status
     * @param string|null $content
     * @param array $headers
     * @return ResponseInterface
     */
    public function error(int $status, string $content = null, array $headers = []): ResponseInterface;

    // TODO: redirection to route
    //public function redirectToRoute($route, $parameters = [], $status = 302, $headers = []): ResponseFactoryInterface;
}