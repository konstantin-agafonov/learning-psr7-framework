<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Pipeline
{
    private \SplQueue $queue;

    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    public function pipe($middleware): Pipeline
    {
        $new = clone $this;
        $new->queue->enqueue($middleware);
        return $new;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        $delegate = new Next(
            clone $this->queue,
            $response,
            $next
        );

        return $delegate($request);
    }
}
