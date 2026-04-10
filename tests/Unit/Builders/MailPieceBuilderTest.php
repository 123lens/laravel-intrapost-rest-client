<?php

declare(strict_types=1);

use Budgetlens\Intrapost\DTOs\Address;
use Budgetlens\Intrapost\Enums\MailPieceProduct;
use Budgetlens\Intrapost\Enums\SalesChannelType;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('creates a mail piece with fluent API', function () {
    $client = MockClient::create([
        [
            'ShipmentID' => 'MP-001',
            'PdfLabelData' => base64_encode('pdf-data'),
            'ZplLabelText' => null,
            'Errors' => null,
        ],
    ]);

    $response = $client->mailPiece()
        ->create()
        ->product(MailPieceProduct::Standard)
        ->weight(250)
        ->reference('REF-001')
        ->labelReference1('Label ref 1')
        ->labelReference2('Label ref 2')
        ->orderReference('ORDER-001')
        ->receiver(new Address(
            street: 'Hoofdstraat',
            number: 1,
            zipcode: '1234AB',
            city: 'Amsterdam',
            countryCode: 'NL',
            fullName: 'Jan Jansen',
        ))
        ->labelAsPdf()
        ->send();

    expect($response->shipmentId)->toBe('MP-001')
        ->and($response->getLabelPdf())->toBe('pdf-data');
});

it('creates a mail piece using convenience methods', function () {
    $client = MockClient::create([
        [
            'ShipmentID' => 'MP-002',
            'PdfLabelData' => null,
            'ZplLabelText' => 'ZPL-DATA',
            'Errors' => null,
        ],
    ]);

    $response = $client->mailPiece()
        ->create()
        ->standard()
        ->weight(100)
        ->receiver(
            fn ($addr) => $addr
            ->fullName('Piet')
            ->street('Straat')
            ->number(5)
            ->zipcode('5678CD')
            ->city('Rotterdam')
            ->countryCode('NL')
        )
        ->labelAsZpl()
        ->salesChannel(SalesChannelType::Shopify, 'My Shop', 'shop-1')
        ->send();

    expect($response->shipmentId)->toBe('MP-002')
        ->and($response->zplLabelText)->toBe('ZPL-DATA');
});

it('supports dimensions and options', function () {
    $client = MockClient::create([
        [
            'ShipmentID' => 'MP-003',
            'PdfLabelData' => null,
            'ZplLabelText' => null,
            'Errors' => null,
        ],
    ]);

    $response = $client->mailPiece()
        ->create()
        ->fixedDays()
        ->weight(500)
        ->dimensions(300, 200, 100)
        ->insuredAmount('50.00')
        ->shipmentContent('Contact lenses')
        ->projectCode('CL')
        ->receiver(new Address(
            street: 'Test',
            zipcode: '1111AA',
            city: 'Utrecht',
            countryCode: 'NL',
            fullName: 'Test',
        ))
        ->labelAsPdf()
        ->send();

    expect($response->shipmentId)->toBe('MP-003');
});

it('supports bol.com order id', function () {
    $client = MockClient::create([
        [
            'ShipmentID' => 'MP-004',
            'PdfLabelData' => null,
            'ZplLabelText' => null,
            'Errors' => null,
        ],
    ]);

    $response = $client->mailPiece()
        ->create()
        ->standard()
        ->weight(100)
        ->bolComOrderId('bol-12345')
        ->receiver(new Address(
            street: 'Test',
            zipcode: '1111AA',
            city: 'Test',
            countryCode: 'NL',
            fullName: 'Test',
        ))
        ->labelAsPdf()
        ->send();

    expect($response->shipmentId)->toBe('MP-004');
});
