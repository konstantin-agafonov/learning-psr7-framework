<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();

$name = $request->getQueryParams()['name'] ?? 'Guest';

$response = (new HtmlResponse("Hello, " . $name . "!"))
    ->withHeader("X-Developer", "Elisdn");

(new SapiEmitter())->emit($response);
