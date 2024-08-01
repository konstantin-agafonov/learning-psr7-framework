<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\SingleAction;
use App\Http\Action\HelloAction;
use Framework\Http\Router\ActionResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$routes = new RouteCollection();

$routes->get('home', '/', HelloAction::class);
$routes->get('about', '/about', AboutAction::class);
$routes->get('blog', '/blog', IndexAction::class);
$routes->get('blog_single', '/blog/{id}', SingleAction::class, ['id' => '\d+']);

$router = new Router($routes);

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $callable = $result->getHandler();
    $action = ActionResolver::resolve($callable);
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page!'], 404);
}

$response = $response->withHeader("X-Developer", "Elisdn");

(new SapiEmitter())->emit($response);
