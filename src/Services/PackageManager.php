<?php

namespace Pscibisz\Inpost\Services;

use Pscibisz\Inpost\Services\Enums\Status;
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

        /** Create new shipment by API */
        $shipmentDto = $this->shipmentService->orderShipment($dataJson);

        /** a do while loop is added to wait until the status on the inpost side changes to allow for ordering a parcel pickup.
         * In a production batch I would use symfony/messanger instead of this loop
         */
        $retry = 0;
        do {
            if ($retry === 10) {
                break;
            }

            if ($retry > 0) {
                sleep(1);
            }

            $retry++;
            $shipmentStatus = $this->shipmentService->checkStatus($shipmentDto->id);
        } while ($shipmentStatus->status !== Status::CONFIRMED);

        /** Create a parcel pickup order  */
        $orderDto = $this->dispatchService->dispatch($dataJson, $shipmentDto);

        /** for the purposes of the task, confirmation of placing an order to collect the package */
        echo sprintf(
            "A package has been created, id: %d",
            $shipmentDto->id
            ) . PHP_EOL;
        echo 'Pickup has been requested: ' . PHP_EOL;
        echo json_encode($orderDto->jsonSerialize()) .  PHP_EOL;
    }
}