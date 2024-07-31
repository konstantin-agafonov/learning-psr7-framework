<?php

namespace Framework\Http;
interface ServerRequestInterface
{
    public function getQueryParams(): array;

    public function withQueryParams(array $queryParams): self;

    public function getParsedBody(): array|null;

    public function withParsedBody(array|null $data): self;
}
