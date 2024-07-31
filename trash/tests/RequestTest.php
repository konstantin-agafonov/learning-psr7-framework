<?php

namespace trash\tests;

use Laminas\Diactoros\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testEmpty()
    {
        $request = new Request();

        self::assertEquals([], $request->getQueryParams());
        self::assertNull($request->getParsedBody());
    }

    public function testQueryParams()
    {
        $data = [
            'foo' => 'bar',
            'baz' => '28',
        ];

        $request = (new Request())
            ->withQueryParams($data);

        self::assertEquals($data, $request->getQueryParams());
        self::assertNull($request->getParsedBody());
    }

    public function testParsedBody()
    {
        $data = [
            'foo' => 'bar',
            'baz' => '28',
        ];

        $request = (new Request())
            ->withParsedBody($data);

        self::assertEquals([], $request->getQueryParams());
        self::assertEquals($data, $request->getParsedBody());
    }

}


