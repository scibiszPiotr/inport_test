<?php

namespace Tests\Factory;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\DTOs\DispatchPointDto;
use Pscibisz\Inpost\Factory\DispatchPointFactory;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;

class DispatchPointFactoryTest extends TestCase
{
    public function testCreateReturnsDispatchPointDto(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $factory = new DispatchPointFactory($loggerMock);

        $data = [
            'sender' => [
                'company_name' => 'Test Company',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '123456789',
                'address' => [
                    'street' => 'Main',
                    'building_number' => '1',
                    'city' => 'City',
                    'post_code' => '00-000',
                    'country_code' => 'PL'
                ]
            ],
            'comments' => 'Some comment'
        ];

        $shipmentIds = ['abc123', 'xyz456'];

        $result = $factory->create($data, $shipmentIds);

        $this->assertInstanceOf(DispatchPointDto::class, $result);
        $this->assertSame('Test Company', $result->name);
        $this->assertSame('123456789', $result->phone);
        $this->assertSame('john@example.com', $result->email);
        $this->assertSame($shipmentIds, $result->shipments);
        $this->assertSame('Some comment', $result->comment);
    }
}