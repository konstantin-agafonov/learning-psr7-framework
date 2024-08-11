<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

use App\Http\Action\AboutAction;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\SingleAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HelloAction;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\CredentialsMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterFactory;
use Framework\Http\Application;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Middleware\DispatchMiddleware;
use Framework\Middleware\MaintenanceMiddleware;
use Framework\Middleware\RouterMiddleware;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$params = [
    'debug' => true,
    'users' => [
        'admin' => '1111',
    ],
];

$auth = new BasicAuthMiddleware($params['users']);

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

$app = new Application(new NotFoundHandler());

$app->pipe(new ErrorHandlerMiddleware($params['debug']))
    ->pipe(CredentialsMiddleware::class)
    ->pipe(ProfilerMiddleware::class)
    ->pipe(new RouterMiddleware($router))
    /*->pipe(MaintenanceMiddleware::class)*/
    ->pipe(DispatchMiddleware::class);

$request = ServerRequestFactory::fromGlobals();

$response = $app->run(
    $request,
    new Response()
);

(new SapiEmitter())->emit($response);
