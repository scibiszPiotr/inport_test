<?php

namespace Tests\Factory;

use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\DTOs\ShipmentOrderDto;
use Pscibisz\Inpost\Factory\ShipmentOrderFactory;
use Pscibisz\Inpost\Services\Logger\LoggerInterface;

class ShipmentOrderFactoryTest extends TestCase
{
    public function testCreateFromJsonReturnsShipmentOrderDto(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $factory = new ShipmentOrderFactory($loggerMock);

        $data = [
            'sender' => [
                'company_name' => 'Company A',
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'email' => 'alice@example.com',
                'phone' => '123456789',
                'address' => [
                    'street' => 'Main',
                    'building_number' => '1',
                    'city' => 'Warsaw',
                    'post_code' => '00-000',
                    'country_code' => 'PL'
                ]
            ],
            'receiver' => [
                'company_name' => 'Company B',
                'first_name' => 'Bob',
                'last_name' => 'Jones',
                'email' => 'bob@example.com',
                'phone' => '987654321',
                'address' => [
                    'street' => 'Second',
                    'building_number' => '2',
                    'city' => 'Krakow',
                    'post_code' => '30-001',
                    'country_code' => 'PL'
                ]
            ],
            'parcels' => [
                [
                    'id' => 'package_1',
                    'dimensions' => [
                        'length' => "100",
                        'width' => "50",
                        'height' => "30",
                        'unit' => 'mm'
                    ],
                    'weight' => [
                        'amount' => "2",
                        'unit' => 'kg'
                    ],
                    'is_non_standard' => false
                ]
            ],
            'insurance' => [
                'amount' => 1000,
                'currency' => 'PLN'
            ],
            'cod' => [
                'amount' => 50,
                'currency' => 'PLN'
            ],
            'service' => 'inpost_courier_standard',
            'additional_services' => ['sms', 'email'],
            'reference' => 'REF123',
            'comments' => 'Handle with care'
        ];

        $result = $factory->createFromJson($data);

        $this->assertInstanceOf(ShipmentOrderDto::class, $result);
        $this->assertSame('REF123', $result->reference);
        $this->assertSame('Handle with care', $result->comments);
        $this->assertSame('inpost_courier_standard', $result->service);
    }
}