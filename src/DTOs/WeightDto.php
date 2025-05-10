<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class WeightDto implements \JsonSerializable
{
    public function __construct(
        public string $amount,
        public string $unit
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'unit' => $this->unit,
        ];
    }
}