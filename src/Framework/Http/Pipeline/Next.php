<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class Next
{
    private $next;
    private SplQueue $queue;

    public function __construct(
        SplQueue $queue,
        callable $next
    )
    {
        $this->default = $next;
        $this->queue = $queue;
    }

    public function __invoke(
        ServerRequestInterface $request
    ): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request);
        }

        return ($this->queue->dequeue())(
            $request,
            function (ServerRequestInterface $request) {
                return $this($request);
            }
        );
    }
}