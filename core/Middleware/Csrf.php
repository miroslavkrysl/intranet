<?php


namespace Core\Middleware;


use Core\Contracts\Http\RequestInterface;
use Core\Http\Middleware;

abstract class Csrf extends Middleware
{

    public function before(RequestInterface $request) {
        /*if ($request->method() == 'get'
            or $this->tokensMatch($request)) {

            return null;
        }*/
        return false;
    }


    public function tokensMatch(RequestInterface $request): bool
    {
        $token = $this->tokenFromRequest($request);

        return is_string(session('csrf.token')) &&
            is_string($token) &&
            hash_equals(session('csrf.token'), $token);
    }

    private function tokenFromRequest($request)
    {
    }
}