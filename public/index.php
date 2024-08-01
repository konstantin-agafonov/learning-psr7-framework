<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$routes = new RouteCollection();

$routes->get('home', '/', function (ServerRequestInterface $request) {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    return new HtmlResponse("Hello, " . $name . "!");
});

$routes->get('about', '/about', function () {
    return new HtmlResponse("I'm a simple about page.!");
});

$routes->get('blog', '/blog', function () {
    return new JsonResponse([
        ['id' => 1, 'title' => '1 Hello World!'],
        ['id' => 2, 'title' => '2 Hello World!'],
    ]);
});

$routes->get('blog_single', '/blog/{id}', function (ServerRequestInterface $request) {
    $id = $request->getAttribute('id');
    if ($id > 5) {
        return new JsonResponse(['error' => 'Undefined page!'], 404);
    }

    return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
}, ['id' => '\d+']);

$router = new Router($routes);

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $result->getHandler();
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page!'], 404);
}

$response = $response->withHeader("X-Developer", "Elisdn");

(new SapiEmitter())->emit($response);
