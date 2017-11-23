<?php


namespace Core\Http;


use Core\Contracts\Http\ResponseFactoryInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\View\ViewFactoryInterface;


class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * Texts corresponding to status codes.
     * @var array
     */
    private $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Reserved for WebDAV advanced collections expired proposal',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    );
    /**
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     * ResponseFactory constructor.
     * @param ViewFactoryInterface $viewFactory
     */
    public function __construct(ViewFactoryInterface $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * Create a new html response.
     * @param string $viewName
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    public function html(string $viewName, array $data = [], int $status = 200, array $headers = []): ResponseInterface
    {
        $view = $this->viewFactory->view($viewName);
        $response = new HtmlResponse($view, $data);
        $response->status($status);
        $response->header($headers);

        return $response;
    }

    /**
     * Create a new json response.
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return ResponseInterface
     */
    public function json(array $data = [], int $status = 200, array $headers = []): ResponseInterface
    {
        $response = new JsonResponse($data);
        $response->status($status);
        $response->header($headers);

        return $response;
    }

    /**
     * Create a new file download response.
     * @param string $filename
     * @param string $name
     * @param array $headers
     * @return ResponseInterface
     */
    public function download(string $filename, string $name = null, array $headers = []): ResponseInterface
    {
        $response = new DownloadResponse($filename, $name);
        $response->header($headers);

        return $response;
    }

    /**
     * Create a new redirection response.
     * @param string $path
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    public function redirect(string $path, int $status = 302, array $headers = []): ResponseInterface
    {
        $response = new RedirectResponse($path);
        $response->status($status);
        $response->header($headers);

        return $response;
    }

    /**
     * Create a new error response.
     * @param int $status
     * @param string $message
     * @param array $headers
     * @return ResponseInterface
     */
    public function error(int $status, string $message = null, array $headers = []): ResponseInterface
    {
        $message = $message ?? $status . " " . $this->statusTexts[$status];

        $data = [
            'title' => $status . ' ' . $this->statusTexts[$status],
            'message' => $message
        ];

        return $this->html('base.wide-message', $data, $status, $headers);
    }

    /**
     * Create a new jsonError response.
     * @param int $status
     * @param string $message
     * @param array $headers
     * @return ResponseInterface
     */
    public function jsonError(int $status, string $message = null, array $headers = []): ResponseInterface
    {
        $message = $message ?? $status . " " . $this->statusTexts[$status];

        $data = [
            'message' => $message
        ];

        return $this->json($data, $status, $headers);
    }
}