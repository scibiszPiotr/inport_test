<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\DTOs\ShipmentDto;
use Pscibisz\Inpost\Factory\ShipmentResponseFactory;
use Pscibisz\Inpost\Services\Enums\ApiEndpoint;
use Pscibisz\Inpost\Services\HttpClients\HttpClientInterface;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;
use Pscibisz\Inpost\Services\ShipmentService;
use Tests\Data\OrderSeeds;

class ShipmentServiceTest extends TestCase
{
    public function testOrderShipmentReturnsId(): void
    {
        $inputData = OrderSeeds::$data;

        $expectedId = new ShipmentDto(42);
        $fakeApiResponse = json_encode(['id' => $expectedId->id]);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->method('post')->willReturn($fakeApiResponse);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())
            ->method('info')
            ->with($this->stringContains('Response from API'));

        $shipmentService = new ShipmentService(
            $httpClientMock,
            $loggerMock,
            new ShipmentResponseFactory($this->createMock(LoggerInterface::class)),
        );

        $result = $shipmentService->orderShipment($inputData);

        $this->assertEquals($expectedId, $result);
    }
}