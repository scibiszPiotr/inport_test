<?php

namespace Pscibisz\Inpost\Factory;

use Pscibisz\Inpost\DTOs\DispatchPointDto;
use Pscibisz\Inpost\DTOs\SenderDto;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;
use Pscibisz\Inpost\Services\Serializer\Serializer as CustomSerializer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

final class DispatchPointFactory
{
    private Serializer $serializer;

    public function __construct(private readonly LoggerInterface $logger)
    {
        $this->serializer = new CustomSerializer()->serializer;
    }

    /** I create the same shipping location from the shipment data,
     * which is the sender's address + I add the ID of the previously created package
     */
    public function create(array $data, array $shipmentIds): ?DispatchPointDto
    {
        try {
            /** @var SenderDto $sender */
            $sender = $this->serializer->denormalize($data['sender'], SenderDto::class);
            $comments = $data['comments'] ?? null;

            return new DispatchPointDto(
                $shipmentIds,
                $comments,
                $sender->companyName ?? $sender->firstName . " " . $sender->lastName,
                $sender->phone,
                $sender->email,
                $sender->address,
            );
        } catch (ExceptionInterface $e) {
            $this->logger->info($e->getMessage());

            return null;
        }
    }
}