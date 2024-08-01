<?php

namespace App\Http\Action;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class AboutAction
{
    public function __invoke(ServerRequestInterface $request = null): HtmlResponse
    {
        return new HtmlResponse("I'm a simple about page.!");
    }
}
