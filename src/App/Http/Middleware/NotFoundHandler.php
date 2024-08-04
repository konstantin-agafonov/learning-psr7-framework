<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundHandler
{
    public function __invoke(
        ServerRequestInterface $request
    ): ResponseInterface
    {
        return new HtmlResponse('Not Found!', 404);
    }
}
