<?php


namespace Intranet\Http\Middleware;
use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Services\Auth\Auth;


/**
 * Redirect non-logged users to login page.
 * @package Intranet\Http\Middleware
 */
class RestrictToLogged
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * Csrf constructor.
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Middleware before method.
     * @param RequestInterface $request
     * @return ResponseInterface|null
     */
    public function before(RequestInterface $request)
    {
        if (!$this->auth->isLogged()){
            if ($request->json()) {
                return \jsonError(401, ['auth' => [\text('app.auth.not_logged')]]);
            }
            if ($request->ajax()) {
                return \html('components.not_logged');
            }
            return \redirect('/login');
        }

        return null;
    }
}