<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

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

    public function match(ServerRequestInterface $request): Result|null
    {
        if (
            $this->methods
            && !in_array($request->getMethod(), $this->methods, true)
        ) {
            return null;
        }

        $pattern = preg_replace_callback(
            '~\{([^\}]+)\}~',
            function ($matches) {
                $argument = $matches[1];
                $replace = $this->tokens[$argument] ?? '[^}]+';
                return '(?P<' . $argument . '>' . $replace . ')';
            },
            $this->pattern
        );

        if (
            preg_match(
                '~^' . $pattern . '$~i',
                $request->getUri()->getPath(),
                $matches
            )
        ) {
            return new Result(
                $this->getName(),
                $this->handler,
                array_filter(
                    $matches,
                    '\is_string',
                    ARRAY_FILTER_USE_KEY
                )
            );
        }

        return null;
    }

    public function generate(string $name, array $params = []): string|null
    {
        $arguments = array_filter($params);

        if ($name !== $this->getName()) {
            return null;
        }

        $url = preg_replace_callback(
            '~\{([^\}]+)\}~',
            function ($matches) use (&$arguments) {
                $argument = $matches[1];
                if (!array_key_exists($argument, $arguments)) {
                    throw new \InvalidArgumentException('Missing argument: ' . $argument);
                }
                return $arguments[$argument];
            },
            $this->pattern
        );

        return $url;
    }
}
