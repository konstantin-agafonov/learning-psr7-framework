<?php

namespace App\Http\Action\Blog;

use App\Http\Action\HelloAction;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class HelloActionTest extends TestCase
{
    public function testGuest()
    {
        $action = new HelloAction();
        $request = new ServerRequest();
        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('Hello, Guest!', $response->getBody()->getContents());
    }

    public function testUser()
    {
        $action = new HelloAction();
        $request = (new ServerRequest())
            ->withQueryParams(['name' => 'John Doe']);
        $response = $action($request);

        self::assertEquals('Hello, John Doe!', $response->getBody()->getContents());
    }
}
