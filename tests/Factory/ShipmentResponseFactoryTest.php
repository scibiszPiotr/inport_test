<?php

namespace Factory;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\DTOs\ShipmentStatusDto;
use Pscibisz\Inpost\Factory\ShipmentResponseFactory;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;

class ShipmentResponseFactoryTest extends TestCase
{
    public function testCreateShipmentFromJson(): void
    {
        $json = json_encode([
            'id' => 123,
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $factory = new ShipmentResponseFactory($logger);

        $dto = $factory->createShipmentFromJson($json);

        $this->assertSame(123, $dto->id);
    }

    public function testCreateStatusFromJson(): void
    {
        $json = json_encode([
            'status' => 'created',
            'id' => 1234567890,
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $factory = new ShipmentResponseFactory($logger);

        $dto = $factory->createStatusFromJson($json);

        $this->assertSame('created', $dto->status->value);
        $this->assertSame(1234567890, $dto->id);
    }
}