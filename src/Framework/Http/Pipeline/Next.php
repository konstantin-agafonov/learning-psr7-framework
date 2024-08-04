<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class Next
{
    private $default;
    private SplQueue $queue;

    public function __construct(
        SplQueue $queue,
        callable $default
    )
    {
        $this->default = $default;
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