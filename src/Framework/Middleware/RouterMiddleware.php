<?php

namespace Framework\Middleware;

use Framework\Http\MiddlewareResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class RouterMiddleware
{
    public function __construct(private Router $router) {}

    public function __invoke(
        ServerRequestInterface $request,
        callable               $next
    )
    {
        try {
            $result = $this->router->match($request);
            foreach ($result->getAttributes() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }
            $middleware = MiddlewareResolver::resolve($result->getHandler());
            return $middleware($request, $next);
        } catch (RequestNotMatchedException $e) {
            return $next($request);
        }
    }
}
