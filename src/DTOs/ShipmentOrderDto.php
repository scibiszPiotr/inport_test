<?php

namespace Pscibisz\Inpost\DTOs;

use JsonSerializable;

final readonly class ShipmentOrderDto implements JsonSerializable
{
    public function __construct(
        public SenderDto $sender,
        public ReceiverDto $receiver,
        public ParcelCollection $parcels,
        public ?InsuranceDto $insurance,
        public ?CodDto $cod,
        public string $service,
        public array $additionalServices,
        public string $reference,
        public string $comments
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'sender' => $this->sender->jsonSerialize(),
            'receiver' => $this->receiver->jsonSerialize(),
            'parcels' => $this->parcels->jsonSerialize(),
            'service' => $this->service,
            'additional_services' => $this->additionalServices,
            'reference' => $this->reference,
            'comments' => $this->comments,
            'insurance' => $this->insurance->jsonSerialize(),
            'cod' =>  $this->cod->jsonSerialize(),
        ];
    }
}