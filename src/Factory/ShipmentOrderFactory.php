<?php

namespace Pscibisz\Inpost\Factory;

use Pscibisz\Inpost\DTOs\CodDto;
use Pscibisz\Inpost\DTOs\InsuranceDto;
use Pscibisz\Inpost\DTOs\ParcelCollection;
use Pscibisz\Inpost\DTOs\ParcelDto;
use Pscibisz\Inpost\DTOs\ReceiverDto;
use Pscibisz\Inpost\DTOs\SenderDto;
use Pscibisz\Inpost\DTOs\ShipmentOrderDto;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;
use Pscibisz\Inpost\Services\Serializer\Serializer as CustomSerializer;

final class ShipmentOrderFactory
{
    private Serializer $serializer;

    public function __construct(private readonly LoggerInterface $logger)
    {
        $this->serializer = new CustomSerializer()->serializer;
    }

    /** Creating DTO from sample Json data contained in data/shipment.json file */
    public function createFromJson(array $data): ?ShipmentOrderDto
    {
        try {
            return $this->serializer->denormalize($data, ShipmentOrderDto::class);
        } catch (ExceptionInterface $e) {
            $this->logger->info($e->getMessage());

            return null;
        }
    }
}