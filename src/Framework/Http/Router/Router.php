<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    public function __construct(
        private RouteCollection $routes
    )
    {

    }

    public function match(ServerRequestInterface $request): Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            if (preg_match($route->getPattern(), $request->getUri()->getPath(), $matches)) {
                return new Result(
                    $route->getName(),
                    $route->getHandler(),
                    $route->getHandler(),
                    $matches
                );
            }
        }

        throw new RequestNotMatchedException($request);
    }

    public function generate(string $name, array $params = []): string
    {
        return '/' . implode('/', $params);
    }

}