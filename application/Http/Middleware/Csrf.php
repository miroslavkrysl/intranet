<?php


namespace Intranet\Http\Middleware;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Http\Middleware;
use Intranet\Services\Csrf\Csrf as CsrfService;

class Csrf
{
    /**
     * Service for handling csrf tokens.
     * @var CsrfService
     */
    private $csrf;

    /**
     * Csrf constructor.
     * @param CsrfService $csrf
     */
    public function __construct(CsrfService $csrf)
    {
        $this->csrf = $csrf;
        $this->csrf->has() ?: $this->csrf->generate();
    }

    /**
     * Middleware before method.
     * @param RequestInterface $request
     * @return ResponseInterface|null
     */
    public function before(RequestInterface $request)
    {
        if ($request->method() == 'get'){
            return null;
        }

        $token = $request->post('_token') ?? $request->header('X-CSRF-TOKEN');

        if ($token and $this->csrf->matches($token)) {
            return null;
        }

        $response = $request->ajax() ?
            \jsonError(401, \text('csrf.inactive')) :
            \error(401, \text('csrf.inactive'));

        return $response;
    }
}