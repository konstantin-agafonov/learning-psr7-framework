<?php

namespace App\Http\Action\Blog;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class ShowActionTest extends TestCase
{
    public function testSuccess()
    {
        $action = new SingleAction();
        $request = (new ServerRequest())
            ->withAttribute('id', $id = 2);
        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(
            json_encode(['id' => $id, 'title' => 'Post #' . $id]),
            $response->getBody()->getContents()
        );
    }

    public function testNotFound()
    {
        $action = new SingleAction();
        $request = (new ServerRequest())
            ->withAttribute('id', $id = 20);
        $response = $action($request);

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('Undefined page!', $response->getBody()->getContents());
    }
}
