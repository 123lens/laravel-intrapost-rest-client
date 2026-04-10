<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('gets an order by reference', function () {
    $client = MockClient::create([
        [
            'OrderData' => base64_encode('order-form-pdf'),
            'Errors' => null,
        ],
    ]);

    $response = $client->mailPiece()->order('ORDER-REF-001');

    expect($response->getOrderPdf())->toBe('order-form-pdf')
        ->and($response->hasErrors())->toBeFalse();
});

it('gets a label by shipment id', function () {
    $client = MockClient::create([
        [
            'PdfLabelData' => base64_encode('label-pdf'),
            'ZplLabelText' => null,
            'Errors' => null,
        ],
    ]);

    $response = $client->mailPiece()->getLabel('MP-001');

    expect($response->getLabelPdf())->toBe('label-pdf');
});
