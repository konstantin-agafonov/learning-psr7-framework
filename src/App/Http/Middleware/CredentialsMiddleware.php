<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class CredentialsMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        callable $next
    ) {
        $response = $next($request);
        return $response->withHeader("X-Developer", "Elisdn");
    }
}
