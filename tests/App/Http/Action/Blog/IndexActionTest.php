<?php

namespace App\Http\Action\Blog;

use App\Http\Action\HelloAction;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class IndexActionTest extends TestCase
{
    public function testSuccess()
    {
        $action = new IndexAction();
        $response = $action();

        self::assertEquals(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString(
            json_encode([
                ['id' => 1, 'title' => '1 Hello World!'],
                ['id' => 2, 'title' => '2 Hello World!'],
            ]),
            $response->getBody()->getContents()
        );
    }
}
