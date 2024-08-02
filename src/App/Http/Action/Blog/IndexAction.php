<?php

namespace App\Http\Action\Blog;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class IndexAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            ['id' => 1, 'title' => '1 Hello World!'],
            ['id' => 2, 'title' => '2 Hello World!'],
        ]);
    }
}
