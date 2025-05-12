<?php

use GuzzleHttp\Client;
use Pscibisz\Inpost\Services\DispatchService;
use Pscibisz\Inpost\Services\HttpClients\HttpClient;
use Pscibisz\Inpost\Services\Logger\LoggerToFile;
use Pscibisz\Inpost\Services\PackageManager;
use Pscibisz\Inpost\Services\ReadData\ReadData;
use Pscibisz\Inpost\Services\ShipmentService;

require_once __DIR__ . '/vendor/autoload.php';

/** Load .env to application */
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__, '.env');
$dotenv->safeLoad();

$logger = new LoggerToFile();

/** Run application, this file is similar to symfony://public/index.php + workaround DI */
try {
    $httpClient = new HttpClient(
        new Client(),
        getenv('ORGANIZATION_ID'),
        getenv('INPOST_TOKEN'),
    );
    $logger = new LoggerToFile();
    new PackageManager(
        new ReadData('shipment.json'), //simplifying the provision of data for creating a package
        new ShipmentService(
            $httpClient,
            $logger,
        ),
        new DispatchService(
            $logger,
            $httpClient,
        ),
    )
        ->handleTask();
} catch (Exception $exception) {
    $logger->info($exception->getMessage());
    echo $exception->getMessage() . PHP_EOL;
}
