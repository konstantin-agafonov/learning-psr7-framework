<?php

namespace Tests\Framework\Http;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testCorrectMethod()
    {
        $routes = new RoutesCollection();

        $routes->get($nameGet = 'blog', '/blog', $handlerGet = 'handler_get');
        $routes->post($namePost = 'blog_edit', '/blog', $handlerPost = 'handler_post');

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        self::assertEquals($nameGet, $result->getName());
        self::assertEquals($handlerGet, $result->getHandler());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        self::assertEquals($namePost, $result->getName());
        self::assertEquals($handlerPost, $result->getHandler());
    }

    
    private function buildRequest($method, $uri): ServerRequest
    {
        return (new ServerRequest())
            ->withMethod($method)
            ->withUri($uri);
    }
}


