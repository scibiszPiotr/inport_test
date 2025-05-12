<?php

namespace Pscibisz\Inpost\Services;

use Pscibisz\Inpost\DTOs\ShipmentDto;
use Pscibisz\Inpost\DTOs\ShipmentStatusDto;
use Pscibisz\Inpost\Factory\ShipmentOrderFactory;
use Pscibisz\Inpost\Factory\ShipmentResponseFactory;
use Pscibisz\Inpost\Services\Enums\ApiEndpoint;
use Pscibisz\Inpost\Services\HttpClients\HttpClientInterface;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;

class ShipmentService
{
    public function  __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private ShipmentResponseFactory $shipmentResponseFactory,
    ) {
    }
    public function orderShipment(array $data): ShipmentDto
    {
        $shipmentOrderDto = new ShipmentOrderFactory($this->logger)->createFromJson($data);

        if ($shipmentOrderDto === null) {
            throw new \RuntimeException('ShipmentOrderDto is not created');
        }

        $shipmentResponse = $this->httpClient->post(
            ApiEndpoint::API_URL->value,
            ApiEndpoint::SHIPMENTS_CREATE->value,
            $shipmentOrderDto
        );

        /** Logging for the entire response task content from API */
        $this->logger->info('Response from API after create Shipment: '. $shipmentResponse);

        return $this->shipmentResponseFactory->createShipmentFromJson($shipmentResponse);
    }

    public function checkStatus(int $id): ShipmentStatusDto
    {
        $shipmentResponse = $this->httpClient->get(
            ApiEndpoint::API_URL->value,
            ApiEndpoint::SHIPMENTS_DETAILS->value . $id,
            $id
        );
        /** Logging for the entire response task content from API */
        $this->logger->info('Response from API after check status of Shipment by id: '. $shipmentResponse);

        return $this->shipmentResponseFactory->createStatusFromJson($shipmentResponse);
    }
}