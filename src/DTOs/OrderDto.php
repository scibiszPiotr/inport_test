<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class OrderDto implements \JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly string $href,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'href' => $this->href,
        ];
    }
}