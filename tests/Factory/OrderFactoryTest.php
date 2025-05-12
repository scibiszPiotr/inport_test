<?php

namespace Tests\Factory;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\DTOs\OrderDto;
use Pscibisz\Inpost\Factory\OrderFactory;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;

class OrderFactoryTest extends TestCase
{
    public function testCreateFromJsonReturnsOrderDto(): void
    {
        $json = json_encode([
            'id' => 12345,
            'status' => 'confirmed',
            'href' => 'https://example.com',
            'created_at' => '2020-01-01T00:00:00+00:00',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $factory = new OrderFactory($logger);

        $dto = $factory->createFromJson($json);

        $this->assertInstanceOf(OrderDto::class, $dto);
        $this->assertSame(12345, $dto->id);
        $this->assertSame('confirmed', $dto->status);
    }
}