<?php

namespace Framework\Http\Router;

class Route
{
    public function __construct(
        public string $name,
        public string $pattern,
        public $handler,
        public array $methods,
        public array $tokens = [],
    )
    {

    }

    public function getName(): string
    {
        return $this->name;
    }
}
