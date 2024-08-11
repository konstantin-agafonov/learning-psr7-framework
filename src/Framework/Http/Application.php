<?php

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewareResolver;
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

    public function run(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        return $this($request, $response, $this->default);
    }
}
