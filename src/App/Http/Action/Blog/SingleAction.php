<?php

namespace App\Http\Action\Blog;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class SingleAction
{
    public function __invoke(
        ServerRequestInterface $request,
        callable $next,
    ): Response
    {
        $id = $request->getAttribute('id');
        if ($id > 5) {
            return $next($request);
        }

        return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
    }
}
