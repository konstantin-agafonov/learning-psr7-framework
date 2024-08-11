<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class Next
{
    private $next;
    private SplQueue $queue;
    private ResponseInterface $response;

    public function __construct(
        SplQueue $queue,
        ResponseInterface $response,
        callable $next
    )
    {
        $this->default = $next;
        $this->response = $response;
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
            $this->response,
            function (ServerRequestInterface $request) {
                return $this($request);
            }
        );
    }
}