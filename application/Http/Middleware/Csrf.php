<?php


namespace Intranet\Http\Middleware;


use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Core\Http\Middleware;
use Intranet\Services\Csrf\Csrf as CsrfService;

class Csrf extends Middleware
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
     * @return bool
     */
    public function before(RequestInterface $request): bool
    {
        if ($request->method() == 'get'){
            return true;
        }

        $token = $request->post('_token') ?? $request->header('X-CSRF-TOKEN');

        if ($token and $this->csrf->matches($token)) {
            return true;
        }

        $response = $request->ajax() ?
            \response(['error' => \text('csrf.inactive')]) :
            \response(\view('base.wide-message', ['message' => \text('csrf.inactive')]));

        $this->setResponse($response);

        return false;
    }
}