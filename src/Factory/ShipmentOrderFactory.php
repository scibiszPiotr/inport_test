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
use Pscibisz\Inpost\Services\Seriallizer\Serializer as CustomSerializer;

class ShipmentOrderFactory
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
            $sender = $this->serializer->denormalize($data['sender'], SenderDto::class);
            $receiver = $this->serializer->denormalize($data['receiver'], ReceiverDto::class);
            $parcelsData = $data['parcels'] ?? [];
            $parcels = new ParcelCollection();
            foreach ($parcelsData as $parcelData) {
                $parcels->add($this->serializer->denormalize($parcelData, ParcelDto::class));
            }
            $insurance = isset($data['insurance']) ? $this->serializer->denormalize($data['insurance'], InsuranceDto::class) : null;
            $cod = isset($data['cod']) ? $this->serializer->denormalize($data['cod'], CodDto::class) : null;
            $service = $data['service'] ?? '';
            $additionalServices = $data['additional_services'] ?? [];
            $reference = $data['reference'] ?? null;
            $comments = $data['comments'] ?? null;

            return new ShipmentOrderDto(
                $sender,
                $receiver,
                $parcels,
                $insurance,
                $cod,
                $service,
                $additionalServices,
                $reference,
                $comments
            );
        } catch (ExceptionInterface $e) {
            $this->logger->info($e->getMessage());

            return null;
        }
    }
}