<?php

namespace Tests\Framework\Http\Pipeline;

use Framework\Http\Pipeline\MiddlewareResolver;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareResolverTest extends TestCase
{
    /*
     * @dataProvider getValidHandlers
     * @param $handler
     * */
    public function testDirect($handler)
    {
        $middleware = MiddlewareResolver::resolve($handler);

        /* @var ResponseInterface $response */
        $response = $middleware(
            (new ServerRequest())->withAttribute('attribute', $value = 'value'),
            new Response(),
            new NotFoundMiddleware()
        );

        self::assertEquals([$value], $response->getHeader('X-Header'));
    }

    public function getValidHandlers()
    {
        return [
            'Callable Callback' => [function (
                ServerRequestInterface $request,
                callable $next
            ) {
                if ($request->getAttribute('next')) {
                    return $next($request);
                }
                return (new HtmlResponse(''))
                    ->withHeader('X-Header', $request->getAttribute('attribute'));
            }],
            'Callable Class' => [CallableMiddleware::class],
            'Callable Object' => [new CallableMiddleware()],
            'DoublePass Callback' => [function (
                ServerRequestInterface $request,
                ResponseInterface $response,
                callable $next
            ) {
                if ($request->getAttribute('next')) {
                    return $next($request);
                }
                return (new HtmlResponse(''))
                    ->withHeader('X-Header', $request->getAttribute('attribute'));
            }],
            'DoublePass Class' => [DoublePassMiddleware::class],
            'DoublePass Object' => [new DoublePassMiddleware()],
            'PSR15 Class' => [Psr15Middleware::class],
            'PSR15 Object' => [new Psr15Middleware()],
        ];
    }
}


class CallableMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        callable $next
    ): ResponseInterface
    {
        if ($request->getAttribute('next')) {
            return $next($request);
        }

        return (new HtmlResponse(''))
            ->withHeader(
                'X-Header',
                $request->getAttribute('attribute')
            );
    }
}

class DoublePassMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        if ($request->getAttribute('next')) {
            return $next($request);
        }

        return (new HtmlResponse(''))
            ->withHeader(
                'X-Header',
                $request->getAttribute('attribute')
            );
    }
}

class Psr15Middleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        if ($request->getAttribute('next')) {
            return $handler->handle($request);
        }

        return (new HtmlResponse(''))
            ->withHeader(
                'X-Header',
                $request->getAttribute('attribute')
            );
    }
}

class NotFoundMiddleware
{
    public function __invoke(
        ServerRequestInterface $request
    ): ResponseInterface
    {
        return new EmptyResponse(404);
    }
}

class DummyMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        callable $next,
    ): ResponseInterface
    {
        return $next($request)
            ->withHeader('X-Dummy', 'dummy');
    }
}
