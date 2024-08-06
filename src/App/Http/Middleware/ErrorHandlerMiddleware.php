<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ErrorHandlerMiddleware
{
    public function __construct(private bool $debug = false) {}

    public function __invoke(
        ServerRequestInterface $request,
        callable               $next
    )
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            if ($this->debug) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'error' => 'Server Error',
                ]);
            } else {
                return new HtmlResponse('Server Error', 500);
            }
        }
    }
}