<?php

namespace Framework\Http\Router;

class Result
{
    public function __construct(
        private string $name,
        private $handler,
        private array $attributes = [],
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): callable|string|array
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
