<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
    private $default;

    public function __construct(callable $default)
    {
        parent::__construct();
        $this->default = $default;
    }

    public function pipe($middleware): Pipeline
    {
        return parent::pipe(MiddlewareResolver::resolve($middleware));
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this($request, $this->default);
    }
}
