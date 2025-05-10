<?php

namespace Pscibisz\Inpost\Services;

use Pscibisz\Inpost\Factory\DispatchPointFactory;
use Pscibisz\Inpost\Services\Enums\ApiEndpoint;
use Pscibisz\Inpost\Services\HttpClients\HttpClientInterface;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;

class DispatchService
{
    public function __construct(
        private LoggerInterface $logger,
        private HttpClientInterface $httpClient
    ) {
    }

    public function dispatch(array $dataJson, int $shipmentId): void
    {
        $dispatchPointDto = new DispatchPointFactory($this->logger)->create($dataJson, [$shipmentId]);

        if ($dispatchPointDto === null) {
            throw new \RuntimeException('DispatchPointDto is not created');
        }

        $dispatchResponse = $this->httpClient->post(
            ApiEndpoint::API_URL->value,
            ApiEndpoint::DISPATCH_ORDERS->value,
            $dispatchPointDto
        );

        /** Logging for the entire response task content from API */
        $this->logger->info('Response from API after create Dispatch: '. $dispatchResponse);
    }
}