<?php

namespace Framework\Http;

class Request
{
    private array $queryParams = [];
    private array|null $parsedBody = null;

    /*public function __construct(
        private array $queryParams = [],
        private array|null $parsedBody = null,
    )
    {

    }*/

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $queryParams): Request
    {
        $new = clone $this;
        $new->queryParams = $queryParams;
        return $new;
    }

    public function getParsedBody(): array|null
    {
        return $this->parsedBody ?: null;
    }

    public function withParsedBody(array|null $data): Request
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }
}
