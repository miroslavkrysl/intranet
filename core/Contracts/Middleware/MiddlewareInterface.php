<?php


namespace Core\Contracts\Middleware;


use Core\Http\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next);
}