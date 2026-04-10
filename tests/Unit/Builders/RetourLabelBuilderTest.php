<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Enums\TrackTraceProduct;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('creates a retour label by shipment id', function () {
    $client = MockClient::create([
        [
            'TrackTraceLink' => 'https://track.example.com/retour',
            'Barcode' => '3SRETOUR123',
            'PdfLabelData' => base64_encode('retour-pdf'),
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->getRetourLabel()
        ->product(TrackTraceProduct::StandardParcel)
        ->shipmentId('SHIP-001')
        ->send();

    expect($response->trackTraceLink)->toBe('https://track.example.com/retour')
        ->and($response->barcode)->toBe('3SRETOUR123')
        ->and($response->getLabelPdf())->toBe('retour-pdf');
});

it('creates a retour label by VZ code', function () {
    $client = MockClient::create([
        [
            'TrackTraceLink' => null,
            'Barcode' => '3SRETOUR456',
            'PdfLabelData' => base64_encode('retour-pdf-2'),
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->getRetourLabel()
        ->vzCode('VZ12345')
        ->send();

    expect($response->barcode)->toBe('3SRETOUR456')
        ->and($response->getLabelPdf())->toBe('retour-pdf-2');
});
