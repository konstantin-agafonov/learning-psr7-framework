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
        return $this->next($request, $default);
    }

    private function next(
        ServerRequestInterface $request,
        callable $default
    ): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return $default($request);
        }

        return ($this->queue->dequeue())(
            $request,
            function (ServerRequestInterface $request) use ($default) {
                return $this->next($request, $default);
            }
        );
    }
}
