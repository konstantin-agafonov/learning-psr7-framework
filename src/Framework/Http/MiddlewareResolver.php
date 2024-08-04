<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareResolver
{
    public static function resolve($handler): callable
    {
        if (is_array($handler)) {
            return self::createPipe($handler);
        }

        if (is_string($handler)) {
            return function (
                ServerRequestInterface $request,
                callable $next
            ) use ($handler)
            {
                $object = new $handler();
                return $object($request, $next);
            };
        }

        return $handler;
    }

    private static function createPipe(array $handlers): Pipeline
    {
        $pipe = new Pipeline();
        foreach ($handlers as $handler) {
            $pipe->pipe(MiddlewareResolver::resolve($handler));
        }
        return $pipe;
    }
}
