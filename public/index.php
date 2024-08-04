<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\SingleAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HelloAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterFactory;
use Framework\Http\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$params = [
    'users' => [
        'admin' => '1111',
    ],
];

$auth = new BasicAuthMiddleware($params['users']);
$profiler = new ProfilerMiddleware();

$router_factory = new RouterFactory();
$aura_router = $router_factory->newInstance();

// add a simple named route without params
$aura_router->addGet('home', '/', HelloAction::class);
$aura_router->addGet('about', '/about', AboutAction::class);
$aura_router->addGet('blog', '/blog', IndexAction::class);
$aura_router->addGet('blog_single', '/blog/{id}', SingleAction::class)
    ->addTokens(['id' => '\d+']);

$aura_router->addGet(
    'cabinet',
    '/cabinet',
    [
        $auth,
        new CabinetAction(),
    ]
);

$router = new AuraRouterAdapter($aura_router);

$pipeline = new Pipeline();
$pipeline->pipe($profiler);

$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $handlers = $result->getHandler();
    foreach (is_array($handlers) ? $handlers : [$handlers] as $handler) {
        $pipeline->pipe(MiddlewareResolver::resolve($handler));
    }
} catch (RequestNotMatchedException $e) {}

$response = $pipeline($request, new NotFoundHandler());

$response = $response->withHeader("X-Developer", "Elisdn");

(new SapiEmitter())->emit($response);
