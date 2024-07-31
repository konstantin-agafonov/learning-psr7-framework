<?php

namespace Framework\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    public function __construct(
        private string $content,
    ){}

    public function __toString(): string
    {
        return $this->getContents();
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }

    public function detach()
    {
        // TODO: Implement detach() method.
    }

    public function getSize(): ?int
    {
        return mb_strlen($this->content);
    }

    public function tell(): int
    {
        // TODO: Implement tell() method.
    }

    public function eof(): bool
    {
        // TODO: Implement eof() method.
    }

    public function isSeekable(): bool
    {
        // TODO: Implement isSeekable() method.
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        // TODO: Implement seek() method.
    }

    public function rewind(): void
    {
        // TODO: Implement rewind() method.
    }

    public function isWritable(): bool
    {
        // TODO: Implement isWritable() method.
    }

    public function write(string $string): int
    {
        $this->content .= $string;
    }

    public function isReadable(): bool
    {
        // TODO: Implement isReadable() method.
    }

    public function read(int $length): string
    {
        // TODO: Implement read() method.
    }

    public function getContents(): string
    {
        return $this->content;
    }

    public function getMetadata(?string $key = null)
    {
        // TODO: Implement getMetadata() method.
    }
}