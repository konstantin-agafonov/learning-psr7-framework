<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Framework\Http\Request;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$request = new Request(queryParams: $_GET, parsedBody: $_POST);

$name = $request->getQueryParams()['name'] ?? 'Guest';

header("X-Developer: Elisdn");

echo "Hello, " . $name . "!";
