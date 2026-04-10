<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Enums\CarrierType;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('looks up an address', function () {
    $client = MockClient::create([
        [
            'Street' => 'Keizersgracht',
            'City' => 'Amsterdam',
            'Errors' => null,
        ],
    ]);

    $response = $client->utility()->lookupAddress('1015AA', 100);

    expect($response->street)->toBe('Keizersgracht')
        ->and($response->city)->toBe('Amsterdam');
});

it('gets product codes', function () {
    $client = MockClient::create([
        [
            'ProductCodes' => [
                ['ProductCode' => 'SP', 'ParcelTypeNameEng' => 'Standard Parcel', 'ParcelTypeNameNld' => 'Standaard Pakket'],
            ],
            'Errors' => null,
        ],
    ]);

    $response = $client->utility()->productCodes();

    expect($response->productCodes)->toHaveCount(1)
        ->and($response->productCodes[0]['ProductCode'])->toBe('SP');
});

it('finds pickup points for address', function () {
    $client = MockClient::create([
        [
            'CarrierType' => 1,
            'PickUpPoints' => [
                [
                    'CarrierId' => 'PP-001',
                    'Name' => 'Albert Heijn Centrum',
                    'Street' => 'Damstraat',
                    'Number' => 10,
                    'Zipcode' => '1012JM',
                    'City' => 'Amsterdam',
                    'CountryCode' => 'NL',
                    'Distance' => 350,
                    'GeoLocation' => ['Latitude' => 52.3702, 'Longitude' => 4.8952],
                    'OpeningHours' => [
                        ['Day' => 'Monday', 'Time' => '08:00-22:00'],
                    ],
                ],
            ],
            'Errors' => null,
        ],
    ]);

    $response = $client->utility()
        ->pickupPointsForAddress()
        ->zipcode('1012LG')
        ->number(1)
        ->limit(5)
        ->get();

    expect($response->carrierType)->toBe(CarrierType::PostNL)
        ->and($response->pickupPoints)->toHaveCount(1)
        ->and($response->pickupPoints[0]->name)->toBe('Albert Heijn Centrum')
        ->and($response->pickupPoints[0]->distance)->toBe(350)
        ->and($response->pickupPoints[0]->geoLocation->latitude)->toBe(52.3702);
});

it('finds dropoff points for international address', function () {
    $client = MockClient::create([
        [
            'CarrierType' => 4,
            'PickUpPoints' => [
                [
                    'CarrierId' => 'DP-001',
                    'Name' => 'DHL Point Berlin',
                    'Street' => 'Friedrichstraße',
                    'Number' => 50,
                    'Zipcode' => '10117',
                    'City' => 'Berlin',
                    'CountryCode' => 'DE',
                    'Distance' => 500,
                ],
            ],
            'Errors' => null,
        ],
    ]);

    $response = $client->utility()
        ->dropoffPointsForInternationalAddress()
        ->zipcode('10117')
        ->number(50)
        ->countryCode('DE')
        ->get();

    expect($response->carrierType)->toBe(CarrierType::DHL)
        ->and($response->pickupPoints)->toHaveCount(1)
        ->and($response->pickupPoints[0]->city)->toBe('Berlin');
});
