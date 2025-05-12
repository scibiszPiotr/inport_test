<?php

namespace Pscibisz\Inpost\Factory;

use Pscibisz\Inpost\DTOs\OrderDto;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;
use Pscibisz\Inpost\Services\Serializer\Serializer as CustomSerializer;
use Symfony\Component\Serializer\Serializer;

final class OrderFactory
{
    private Serializer $serializer;

    public function __construct(private readonly LoggerInterface $logger)
    {
        $this->serializer = new CustomSerializer()->serializer;
    }

    public function createFromJson(string $data): OrderDto
    {
        return $this->serializer->deserialize($data, OrderDto::class, 'json');
    }
}