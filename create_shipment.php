<?php

/** Use autoload in application */

use Pscibisz\Inpost\Services\PackageManager;

require_once __DIR__ . '/vendor/autoload.php';

/** Load .env to application */
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__, '.env');
$dotenv->safeLoad();

$logger = new \Pscibisz\Inpost\Services\Logger\LoggerToFile();

/** Run application, this file is similar to symfony://public/index.php */
try {
    new PackageManager(getenv('INPOST_TOKEN'), getenv('ORGANIZATION_ID'))
        ->handleTask();
} catch (Exception $exception) {
    $logger->info($exception->getMessage());
    echo $exception->getMessage() . PHP_EOL;
}
