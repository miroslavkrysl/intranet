<?php


namespace Core\Middleware;


use Core\Contracts\Http\RequestInterface;
use Core\Http\Middleware;

abstract class Csrf extends Middleware
{
    /**
     * Middleware before method.
     * @param RequestInterface $request
     * @return bool
     */
    public function before(RequestInterface $request):bool
    {
        if ($request->method() == 'get'
            or $this->tokensMatch($request)) {
            return true;
        }
        return false;
    }

    /**
     * Determine if the csrf request tokens match the records.
     * @param RequestInterface $request
     * @return bool
     */
    public function tokensMatch(RequestInterface $request): bool
    {
        $token = $this->tokenFromRequest($request);

        return is_string(session('csrf.token')) &&
            is_string($token) &&
            hash_equals(session('csrf.token'), $token);
    }

    private function tokenFromRequest($request)
    {
        $token = $request->post('_token') ?: $request->header('X-CSRF-TOKEN');

        if (! $token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header);
        }

        return $token;
    }
}