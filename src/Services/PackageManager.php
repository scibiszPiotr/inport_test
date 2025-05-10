<?php

namespace Pscibisz\Inpost\Services;

use Pscibisz\Inpost\Services\ReadData\ReadDataInterface;

class PackageManager
{
    public function __construct(
        private ReadDataInterface $readData,
        private ShipmentService $shipmentService,
        private DispatchService $dispatchService,
    ) {
    }

    public function handleTask(): void
    {
        /** get data from file */
        $dataJson = $this->readData->get();

        $shipmentId = $this->shipmentService->orderShipment($dataJson);

        $this->dispatchService->dispatch($dataJson, $shipmentId);
    }
}