<?php

namespace Framework\Http\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;

class RouteNotFoundException extends \LogicException
{
    public function __construct(
        private string $name,
        private array $params,
    )
    {
        parent::__construct('Route not found!');
    }

    public function getParams(): ServerRequestInterface
    {
        return $this->params;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
