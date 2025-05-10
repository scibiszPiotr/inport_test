<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class CodDto implements \JsonSerializable
{
    public function __construct(
        public float $amount,
        public string $currency
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }
}