<?php

namespace Framework\Middleware;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class MaintenanceMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        callable $next)
    {
        return new HtmlResponse('maintenance!');
    }
}
