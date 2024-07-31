<?php

namespace Framework\Http;

interface ResponseInterface
{
    public function getStatusCode(): int;
    public function getReasonPhrase(): string;
    public function withHeader(string $name, string $value): self;
    public function withHeaders(array $headers): self;
    public function withStatus(int $status, $reasonPhrase = ''): self;
    public function withBody(string $body): self;
    public function getBody(): string;
    public function getHeaders(): array;
    public function hasHeader(string $name): bool;
    public function getHeader(string $name);
}
