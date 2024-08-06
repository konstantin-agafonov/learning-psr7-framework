<?php

namespace Framework\Middleware;

use Framework\Http\MiddlewareResolver;
use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;

class DispatchMiddleware
{
    public function __construct() {}

    public function __invoke(
        ServerRequestInterface $request,
        callable $next
    )
    {
        if (!$result = $request->getAttribute(Result::class)) {
            return $next($request);
        }
        $middleware = MiddlewareResolver::resolve($result->getHandler());
        return $middleware($request, $next);
    }
}
