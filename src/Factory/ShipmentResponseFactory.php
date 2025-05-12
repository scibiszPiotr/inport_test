<?php

namespace Pscibisz\Inpost\Factory;

use Pscibisz\Inpost\DTOs\ShipmentDto;
use Pscibisz\Inpost\DTOs\ShipmentStatusDto;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;
use Pscibisz\Inpost\Services\Serializer\Serializer as CustomSerializer;
use Symfony\Component\Serializer\Serializer;

final class ShipmentResponseFactory
{
    private Serializer $serializer;

    public function __construct(private readonly LoggerInterface $logger)
    {
        $this->serializer = new CustomSerializer()->serializer;
    }

    public function createShipmentFromJson(string $data): ShipmentDto
    {
        return $this->serializer->deserialize($data, ShipmentDto::class, 'json');
    }

    public function createStatusFromJson(string $data): ShipmentStatusDto
    {
        return $this->serializer->deserialize($data, ShipmentStatusDto::class, 'json');
    }
}