<?php


namespace Core\Http;


use Core\Container\Container;
use Core\Contracts\Http\ResponseFactoryInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Contracts\View\ViewInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * Global container instance.
     * @var Container
     */
    private $container;

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
     * ResponseFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create a new html response.
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    public function html(string $content, int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($content, $headers, $status);
    }

    /**
     * Create a new json response.
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return ResponseInterface
     */
    public function json(array $data = [], int $status = 200, array $headers = [], $options = 0): ResponseInterface
    {
        $content = \json_encode($data, $options);
        $response = (new Response($content, $headers, $status))
            ->header('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Create a new file download response.
     * @param string $filename
     * @param string $name
     * @param array $headers
     * @param string $disposition
     * @return ResponseInterface
     */
    public function download(string $filename, string $name = null, array $headers = [], $disposition = 'attachment'): ResponseInterface
    {
        $content = \file_get_contents($filename);
        $mimeType = \mime_content_type($filename);
        $name = $name ? $name : \basename($filename);

        $response = (new Response($content, $headers))
            ->header('Content-Type', $mimeType)
            ->header('Content-disposition', 'attachment; filename="' . $name . '"');

        return $response;
    }

    public function redirect(string $path, int $status = 302, array $headers = []): ResponseInterface
    {
        $response = (new Response())
            ->status($status)
            ->header('Location', $path)
            ->header('Connection', 'close');

        return $response;
    }


    /**
     * Create a new error response.
     * @param int $status
     * @param string|array $messages
     * @param array $headers
     * @return ResponseInterface
     */
    public function error(int $status, $messages = [], array $headers = []): ResponseInterface
    {
        $messages = \is_string($messages) ? [$messages] : $messages;

        $data = [
            'title' => [$status . ' ' . $this->statusTexts[$status]],
            'messages' => $messages ?: [$status . " " . $this->statusTexts[$status]]
        ];

        $content = $this->container
                ->get('view')
                ->render('base.wide-message', $data);

        return new Response($content, $headers, $status);
    }

    /**
     * Create a new whoops response.
     * @param int $status
     * @param string|null $content
     * @param array $headers
     * @return ResponseInterface
     */
    public function whoops(string $text = null, int $status = 200, array $headers = []): ResponseInterface
    {
        $content = $this->container
            ->get('view')
            ->render('base.whoops', [
                'title' => 'Whoops!',
                'text' => $text ?: text('base.whoops')
            ]);

        return new Response($content, $headers, $status);
    }

    /*
    public function redirectToRoute($route, $parameters = [], $status = 302, $headers = []): ResponseInterface
    {
        // TODO: Implement redirectToRoute() method.
    }
    */
}