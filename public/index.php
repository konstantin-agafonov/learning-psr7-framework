<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\SingleAction;
use App\Http\Action\HelloAction;
use Aura\Router\RouterFactory;
use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$router_factory = new RouterFactory();
$aura_router = $router_factory->newInstance();

// add a simple named route without params
$aura_router->addGet('home', '/', HelloAction::class);
$aura_router->addGet('about', '/about', AboutAction::class);
$aura_router->addGet('blog', '/blog', IndexAction::class);
$aura_router->addGet('blog_single', '/blog/{id}', SingleAction::class)
    ->addTokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura_router);

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
