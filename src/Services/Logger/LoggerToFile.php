<?php

namespace Pscibisz\Inpost\Services\Logger;

final class LoggerToFile implements LoggerInterface
{
    private string $path = __DIR__ . '/../../../log.txt';
    public function info(string $message): void
    {
        /** the simplest solution for logging to a file,
         * the interface allows in the future to connect the logging in a different way, e.g. to db or stdout
         */
        file_put_contents($this->path, PHP_EOL . $message, FILE_APPEND);
    }
}