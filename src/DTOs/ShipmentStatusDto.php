<?php

namespace Pscibisz\Inpost\DTOs;

use Pscibisz\Inpost\Services\Enums\Status;

final readonly class ShipmentStatusDto
{
    public function __construct(
        public readonly int $id,
        public readonly Status $status,
    ) {
    }
}