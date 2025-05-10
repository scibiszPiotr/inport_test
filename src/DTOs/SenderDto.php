<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class SenderDto implements \JsonSerializable
{
    public function __construct(
        public string $companyName,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public AddressDto $address
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'company_name' => $this->companyName,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address->jsonSerialize(),
        ];
    }
}