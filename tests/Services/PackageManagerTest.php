<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\DTOs\OrderDto;
use Pscibisz\Inpost\DTOs\ShipmentDto;
use Pscibisz\Inpost\DTOs\ShipmentStatusDto;
use Pscibisz\Inpost\Services\DispatchService;
use Pscibisz\Inpost\Services\Enums\Status;
use Pscibisz\Inpost\Services\PackageManager;
use Pscibisz\Inpost\Services\ReadData\ReadDataInterface;
use Pscibisz\Inpost\Services\ShipmentService;

class PackageManagerTest extends TestCase
{
    public function testHandleTask(): void
    {
        $data = ['recipient' => 'Jan Kowalski'];
        $shipmentId = new ShipmentDto(1);

        $readDataMock = $this->createMock(ReadDataInterface::class);
        $shipmentServiceMock = $this->createMock(ShipmentService::class);
        $dispatchServiceMock = $this->createMock(DispatchService::class);

        $readDataMock->expects($this->once())
            ->method('get')
            ->willReturn($data);

        $shipmentServiceMock->expects($this->once())
            ->method('orderShipment')
            ->with($data)
            ->willReturn($shipmentId);

        $shipmentServiceMock->expects($this->once())
            ->method('checkStatus')
            ->with($shipmentId->id)
            ->willReturn(new ShipmentStatusDto(2, Status::CONFIRMED));

        $dispatchServiceMock->expects($this->once())
            ->method('dispatch')
            ->with($data, $shipmentId)
            ->willReturn(
                new OrderDto(
                    7654,
                    'created',
                    new \DateTime('2025-05-12 09:21:05')->format('Y-m-d H:i:s'),
                    'http://test.test',
                )
            );

        $packageManager = new PackageManager(
            $readDataMock,
            $shipmentServiceMock,
            $dispatchServiceMock
        );

        ob_start();

        $packageManager->handleTask();

        // Sprawdzenie czy zostaną wydrukowane właściwe informacje na ekran
        $output = ob_get_clean();

        $this->assertEquals('A package has been created, id: 1
Pickup has been requested: 
{"id":7654,"status":"created","createdAt":"2025-05-12 09:21:05","href":"http:\/\/test.test"}
', $output);
    }
}