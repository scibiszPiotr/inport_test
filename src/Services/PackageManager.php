<?php

namespace Pscibisz\Inpost\Services;

use Pscibisz\Inpost\HttpClient;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;
use Pscibisz\Inpost\Services\Logger\LoggerToFile;

class PackageManager
{
    private HttpClient $httpClient;
    private ReadData $readData;
    private LoggerInterface $logger;

    /** This constructor is meant to be a DI workaround, meaning I'll be creating and configuring the rest of the app here */
    public function __construct(
        private readonly string $apiToken,
        private readonly string $organizationId,
    ) {
        $this->httpClient = new HttpClient(
            $this->organizationId,
            $this->apiToken,
        );

        $this->readData = new ReadData('shipment.json');
        $this->logger = new LoggerToFile();
    }

    public function handleTask(): void
    {
        /** get data from file */
        $dataJson = $this->readData->get();

        $shipmentService = new ShipmentService($this->httpClient, $this->logger);
        $shipmentId = $shipmentService->orderShipment($dataJson);

        $dispatchService = new DispatchService($this->logger, $this->httpClient);
        $dispatchService->dispatch($dataJson, $shipmentId);
    }
}