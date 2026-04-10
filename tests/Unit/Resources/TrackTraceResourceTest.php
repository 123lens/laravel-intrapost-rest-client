<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Enums\LabelFormatType;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('cancels a shipment', function () {
    $client = MockClient::create([
        ['Errors' => null],
    ]);

    $response = $client->trackTrace()->cancel('SHIP-001');

    expect($response->isSuccessful())->toBeTrue();
});

it('gets shipments from ids', function () {
    $client = MockClient::create([
        [
            'Shipments' => [
                ['ID' => 'S-001', 'VZCode' => 'VZ001', 'Status' => 4],
                ['ID' => 'S-002', 'VZCode' => 'VZ002', 'Status' => 0],
            ],
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()->getFromId(['S-001', 'S-002']);

    expect($response->shipments)->toHaveCount(2);
});

it('gets shipments from VZ codes', function () {
    $client = MockClient::create([
        [
            'Shipments' => [
                ['ID' => 'S-003', 'VZCode' => 'VZ003', 'Status' => 1],
            ],
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()->getFromVz(['VZ003'], includeHistory: true);

    expect($response->shipments)->toHaveCount(1)
        ->and($response->shipments[0]->vzCode)->toBe('VZ003');
});

it('creates labels for multiple shipments', function () {
    $client = MockClient::create([
        [
            'PdfLabelData' => base64_encode('multi-label-pdf'),
            'ZplLabelText' => null,
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()->createLabels(
        ['S-001', 'S-002', 'S-003'],
        LabelFormatType::Pdf150x100
    );

    expect($response->getLabelPdf())->toBe('multi-label-pdf');
});
