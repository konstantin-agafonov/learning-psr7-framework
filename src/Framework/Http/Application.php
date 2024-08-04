<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;

class Application extends Pipeline
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pipe($middleware): Pipeline
    {
        return parent::pipe(MiddlewareResolver::resolve($middleware));
    }
}
