<?php

namespace Pscibisz\Inpost\Services\Logger;

interface LoggerInterface
{
    public function info(string $message): void;
}