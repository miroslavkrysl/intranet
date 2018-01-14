<?php


namespace Intranet\Http\Middleware;
use Core\Contracts\Http\RequestInterface;
use Core\Contracts\Http\ResponseInterface;
use Intranet\Services\Auth\Auth;


/**
 * Restrict access according to permissions.
 * @package Intranet\Http\Middleware
 */
class RestrictAccess
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
    public function before(RequestInterface $request, array $permissions)
    {
        if (!$this->auth->isLogged()){
            return \redirect('/login');
        }

        return null;
    }
}