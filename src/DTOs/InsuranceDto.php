<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class InsuranceDto implements \JsonSerializable
{
    public float $amount;

    public function __construct(
        int|float $amount,
        public string $currency
    ) {
        $this->amount = $amount;
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }
}