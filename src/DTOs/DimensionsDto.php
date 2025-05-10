<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class DimensionsDto implements \JsonSerializable
{
    public function __construct(
        public string $length,
        public string $width,
        public string $height,
        public string $unit
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'unit' => $this->unit,
        ];
    }
}