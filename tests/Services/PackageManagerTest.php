<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\Services\DispatchService;
use Pscibisz\Inpost\Services\PackageManager;
use Pscibisz\Inpost\Services\ReadData\ReadDataInterface;
use Pscibisz\Inpost\Services\ShipmentService;

class PackageManagerTest extends TestCase
{
    public function testHandleTask(): void
    {
        // Przygotowanie danych
        $data = ['recipient' => 'Jan Kowalski'];
        $shipmentId = 1;

        // Tworzymy mocki zależności
        $readDataMock = $this->createMock(ReadDataInterface::class);
        $shipmentServiceMock = $this->createMock(ShipmentService::class);
        $dispatchServiceMock = $this->createMock(DispatchService::class);

        // Oczekujemy, że metoda get() zostanie wywołana i zwróci dane
        $readDataMock->expects($this->once())
            ->method('get')
            ->willReturn($data);

        // Oczekujemy, że orderShipment zostanie wywołana z danymi i zwróci shipmentId
        $shipmentServiceMock->expects($this->once())
            ->method('orderShipment')
            ->with($data)
            ->willReturn($shipmentId);

        // Oczekujemy, że dispatch zostanie wywołana z danymi i shipmentId
        $dispatchServiceMock->expects($this->once())
            ->method('dispatch')
            ->with($data, $shipmentId);

        // Tworzymy instancję klasy PackageManager
        $packageManager = new PackageManager(
            $readDataMock,
            $shipmentServiceMock,
            $dispatchServiceMock
        );

        // Wywołujemy metodę handleTask
        $packageManager->handleTask();
    }
}