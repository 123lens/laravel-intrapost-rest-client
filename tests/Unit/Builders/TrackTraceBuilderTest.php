<?php

declare(strict_types=1);

use Budgetlens\Intrapost\DTOs\Address;
use Budgetlens\Intrapost\DTOs\CustomsInfo;
use Budgetlens\Intrapost\DTOs\CustomsProduct;
use Budgetlens\Intrapost\Enums\CarrierType;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('creates a track trace shipment with fluent API', function () {
    $client = MockClient::create([
        [
            'TrackTraceLink' => 'https://track.example.com/TT001',
            'VZCode' => 'VZ001',
            'PdfLabelData' => base64_encode('pdf'),
            'ShipmentID' => 'TT-001',
            'ZplLabelText' => null,
            'ProductName' => 'Standard Parcel',
            'SortingHint' => 'B2',
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->create()
        ->standardParcel()
        ->weight(2.5)
        ->reference('REF-TT-001')
        ->orderReference('ORDER-TT-001')
        ->sendMailToRecipient()
        ->receiver(new Address(
            street: 'Prinsengracht',
            number: 100,
            zipcode: '1015DW',
            city: 'Amsterdam',
            countryCode: 'NL',
            fullName: 'Klaas Vaak',
        ))
        ->labelAsPdf()
        ->send();

    expect($response->shipmentId)->toBe('TT-001')
        ->and($response->trackTraceLink)->toBe('https://track.example.com/TT001')
        ->and($response->vzCode)->toBe('VZ001')
        ->and($response->productName)->toBe('Standard Parcel');
});

it('uses product convenience methods', function () {
    $client = MockClient::create([
        ['ShipmentID' => 'TT-002', 'Errors' => null, 'TrackTraceLink' => null, 'VZCode' => null, 'PdfLabelData' => null, 'ZplLabelText' => null, 'ProductName' => null, 'SortingHint' => null],
    ]);

    $response = $client->trackTrace()
        ->create()
        ->eveningDelivery()
        ->weight(1.0)
        ->receiver(new Address(street: 'Test', zipcode: '1111AA', city: 'Test', countryCode: 'NL', fullName: 'Test'))
        ->send();

    expect($response->shipmentId)->toBe('TT-002');
});

it('creates a v3.0 shipment with pickup address', function () {
    $client = MockClient::create([
        [
            'TrackTraceLink' => 'https://track.example.com/TT003',
            'VZCode' => 'VZ003',
            'PdfLabelData' => null,
            'ShipmentID' => 'TT-003',
            'ZplLabelText' => null,
            'ProductName' => 'Pickup Location',
            'SortingHint' => null,
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->create()
        ->pickupLocation()
        ->weight(3.0)
        ->receiver(new Address(street: 'Damrak', number: 1, zipcode: '1012LG', city: 'Amsterdam', countryCode: 'NL', fullName: 'Jan'))
        ->pickupAt(CarrierType::PostNL, 'LOC-123', 'NL')
        ->dimensions(30, 20, 15)
        ->insuredAmount('100.00')
        ->shipmentContent('Contact lenses')
        ->labelAsPdf()
        ->send();

    expect($response->shipmentId)->toBe('TT-003')
        ->and($response->productName)->toBe('Pickup Location');
});

it('creates a v3.0 shipment with customs', function () {
    $client = MockClient::create([
        [
            'ShipmentID' => 'TT-004',
            'Errors' => null,
            'TrackTraceLink' => null,
            'VZCode' => null,
            'PdfLabelData' => null,
            'ZplLabelText' => null,
            'ProductName' => null,
            'SortingHint' => null,
        ],
    ]);

    $customs = new CustomsInfo(
        invoiceNumber: 'INV-001',
        products: [
            new CustomsProduct(
                englishDescription: 'Contact lenses',
                quantity: 10,
                pieceWeight: 50,
                commercialValue: 25.00,
                countryOfOrigin: 'NL',
                hsCode: '9001.30',
            ),
        ],
    );

    $response = $client->trackTrace()
        ->create()
        ->standardParcel()
        ->weight(1.5)
        ->receiver(new Address(street: 'Test', zipcode: '10001', city: 'New York', countryCode: 'US', fullName: 'John'))
        ->customsInfo($customs)
        ->send();

    expect($response->shipmentId)->toBe('TT-004');
});

it('creates a mailbox parcel', function () {
    $client = MockClient::create([
        [
            'TrackTraceLink' => 'https://track.example.com/MBP',
            'VZCode' => 'VZ-MBP',
            'PdfLabelData' => null,
            'ShipmentID' => 'MBP-001',
            'ZplLabelText' => 'ZPL-MBP',
            'ProductName' => 'Mailbox Parcel',
            'SortingHint' => null,
            'Barcode' => 'DHL123456',
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->createMailboxParcel()
        ->weight(800)
        ->reference('MBP-REF')
        ->receiver(
            fn ($addr) => $addr
            ->fullName('Marie')
            ->street('Singel')
            ->number(50)
            ->zipcode('1012AB')
            ->city('Amsterdam')
            ->countryCode('NL')
        )
        ->labelAsZpl()
        ->send();

    expect($response->shipmentId)->toBe('MBP-001')
        ->and($response->barcode)->toBe('DHL123456')
        ->and($response->zplLabelText)->toBe('ZPL-MBP');
});
