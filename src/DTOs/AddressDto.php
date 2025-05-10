<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class AddressDto implements \JsonSerializable
{
    public function __construct(
        public string $street,
        public string $buildingNumber,
        public string $city,
        public string $postCode,
        public string $countryCode
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'street' => $this->street,
            'building_number' => $this->buildingNumber,
            'city' => $this->city,
            'post_code' => $this->postCode,
            'country_code' => $this->countryCode,
        ];
    }
}
