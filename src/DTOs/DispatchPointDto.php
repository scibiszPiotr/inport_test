<?php

namespace Pscibisz\Inpost\DTOs;

final readonly class DispatchPointDto implements \JsonSerializable
{
    /**
     * @param array<string> $shipments
     */
    public function __construct(
        public array $shipments,
        public string $comment,
        public string $name,
        public string $phone,
        public string $email,
        public AddressDto $address
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'shipments' => $this->shipments,
            'comment' => $this->comment,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address->jsonSerialize(),
        ];
    }
}