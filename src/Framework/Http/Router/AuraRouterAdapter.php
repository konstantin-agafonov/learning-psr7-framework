<?php

namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class AuraRouterAdapter implements Router
{
    public function __construct(
        private \Aura\Router\Router $router
    )
    {

    }
    public function match(ServerRequestInterface $request): Result
    {
        if (
            $route = $this
                ->router
                ->match(
                    $request
                        ->getUri()
                        ->getPath(),
                    $request->getServerParams() // $_SERVER
                )
        ) {
            return new Result(
                $route->name,
                $route->params['action'],
                $route->params,
            );
        }

        throw new RequestNotMatchedException($request);
    }

    public function generate(string $name, array $params = []): string
    {
        try {
            return $this->router->generate($name, $params);
        } catch (RouteNotFound $e) {
            throw new RouteNotFoundException($name, $params);
        }
    }
}