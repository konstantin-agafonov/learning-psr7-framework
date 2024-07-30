<?php

namespace Framework\Http;

class Request
{
    public function __construct(
        private array $queryParams = [],
        private array|null $parsedBody = null,
    )
    {

    }
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParsedBody(): array|null
    {
        return $this->parsedBody ?: null;
    }
}
