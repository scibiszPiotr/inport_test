<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class ParcelDto implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public DimensionsDto $dimensions,
        public WeightDto $weight,
        public bool $isNonStandard
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'dimensions' => $this->dimensions->jsonSerialize(),
            'weight' => $this->weight->jsonSerialize(),
            'is_non_standard' => $this->isNonStandard,
        ];
    }
}