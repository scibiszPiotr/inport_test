<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\DTOs\ShipmentDto;
use Pscibisz\Inpost\Factory\ShipmentResponseFactory;
use Pscibisz\Inpost\Services\HttpClients\HttpClientInterface;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;
use Pscibisz\Inpost\Services\ShipmentService;
use ReflectionClass;
use Tests\Data\OrderSeeds;

class DispatchServiceTest extends TestCase
{
    public function testOrderShipmentReturnsShipmentId(): void
    {
        $inputData = OrderSeeds::$data;

        $expectedId = new ShipmentDto(99);
        $fakeResponse = json_encode(['id' => $expectedId->id]);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->method('post')->willReturn($fakeResponse);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())
            ->method('info')
            ->with($this->stringContains('Response from API'));

        $shipmentService = new ShipmentService(
            $this->createMock(HttpClientInterface::class),
            $this->createMock(LoggerInterface::class),
            new ShipmentResponseFactory($this->createMock(LoggerInterface::class)),
        );

        $reflection = new ReflectionClass($shipmentService);

        foreach (['httpClient' => $httpClientMock, 'logger' => $loggerMock] as $property => $mock) {
            $prop = $reflection->getProperty($property);
            $prop->setAccessible(true);
            $prop->setValue($shipmentService, $mock);
        }

        $result = $shipmentService->orderShipment($inputData);

        $this->assertEquals($expectedId, $result);
    }
}