<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

class CabinetAction
{
    public function __invoke(
        ServerRequestInterface $request
    ): Response
    {
        throw new RuntimeException('huyak!');

        return new HtmlResponse(
            'I am ' . $request->getAttribute(
                BasicAuthMiddleware::ATTRIBUTE
            )
        );
    }
}
