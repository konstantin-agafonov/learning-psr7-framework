<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

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
                ResponseInterface $response,
                callable $next
            ) use ($handler)
            {
                $middleware = self::resolve(new $handler());
                return $middleware($request, $response, $next);
            };
        }

        if ($handler instanceof MiddlewareInterface) {
            return function(
                ServerRequestInterface $request,
                ResponseInterface $response,
                callable $next
            ) use ($handler)
            {
                return $handler->process(
                    $request,
                    new InteropHandlerWrapper($next)
                );
            };
        }

        if (is_object($handler)) {
            $reflection = new \ReflectionObject($handler);
            if
        }

        return $handler;
    }

    private static function createPipe(array $handlers): Pipeline
    {
        $pipe = new Pipeline();
        foreach ($handlers as $handler) {
            $pipe->pipe(self::resolve($handler));
        }
        return $pipe;
    }
}
