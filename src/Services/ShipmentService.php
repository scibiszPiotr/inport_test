<?php

namespace Pscibisz\Inpost\Services;

use Pscibisz\Inpost\Factory\ShipmentOrderFactory;
use Pscibisz\Inpost\Services\Enums\ApiEndpoint;
use Pscibisz\Inpost\Services\HttpClients\HttpClientInterface;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;

class ShipmentService
{
    public function  __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
    ) {
    }

    public function orderShipment(array $data): int
    {
        $shipmentOrderDto = new ShipmentOrderFactory($this->logger)->createFromJson($data);

        if ($shipmentOrderDto === null) {
            throw new \RuntimeException('ShipmentOrderDto is not created');
        }

        $shipmentResponse = $this->httpClient->post(
            ApiEndpoint::API_URL->value,
            ApiEndpoint::ORDER_SHIPMENTS->value,
            $shipmentOrderDto
        );

        /** Logging for the entire response task content from API */
        $this->logger->info('Response from API after create Shipment: '. $shipmentResponse);

        $shipment = json_decode($shipmentResponse, true);

        return $shipment['id'];
    }
}