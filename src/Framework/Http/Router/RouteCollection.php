<?php

namespace Framework\Http\Router;

class RouteCollection
{
    private $routes = [];

    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function add(
        string $name,
        string $pattern,
        callable $handler,
        array $methods,
        array $tokens = []
    ): void
    {
        $this->addRoute(new Route(
            name: $name,
            pattern: $pattern,
            handler: $handler,
            methods: $methods,
            tokens: $tokens,
        ));
    }

    public function any(
        string $name,
        string $pattern,
        callable $handler,
        array $tokens = []
    ): void
    {
        $this->addRoute(new Route(
            name: $name,
            pattern: $pattern,
            handler: $handler,
            methods: [],
            tokens: $tokens,
        ));
    }

    public function get(
        string $name,
        string $pattern,
        callable $handler,
        array $tokens = []
    ): void
    {
        $this->addRoute(new Route(
            name: $name,
            pattern: $pattern,
            handler: $handler,
            methods: ['GET'],
            tokens: $tokens,
        ));
    }

    public function post(
        string $name,
        string $pattern,
        callable $handler,
        array $tokens = []
    ): void
    {
        $this->addRoute(new Route(
            name: $name,
            pattern: $pattern,
            handler: $handler,
            methods: ['POST'],
            tokens: $tokens,
        ));
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

}