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

    public function pipe(callable $callback): Pipeline
    {
        $new = clone $this;
        $new->queue->enqueue($callback);
        return $new;
    }

    public function __invoke(
        ServerRequestInterface $request,
        callable $default
    ): ResponseInterface
    {
        $delegate = new Next(clone $this->queue, $default);

        return $delegate($request);
    }
}
