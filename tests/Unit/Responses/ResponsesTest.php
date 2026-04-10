<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Responses\CancelResponse;
use Budgetlens\Intrapost\Responses\DailyMailOrderResponse;
use Budgetlens\Intrapost\Responses\GetLabelResponse;
use Budgetlens\Intrapost\Responses\LookupAddressResponse;
use Budgetlens\Intrapost\Responses\MailPieceCreateResponse;
use Budgetlens\Intrapost\Responses\MailPieceOrderResponse;
use Budgetlens\Intrapost\Responses\ProductCodesResponse;
use Budgetlens\Intrapost\Responses\SearchResponse;
use Budgetlens\Intrapost\Responses\TrackTraceCreateResponse;

it('creates mail piece response from array', function () {
    $response = MailPieceCreateResponse::fromArray([
        'ShipmentID' => 'abc-123',
        'PdfLabelData' => base64_encode('fake-pdf'),
        'ZplLabelText' => null,
        'Errors' => null,
    ]);

    expect($response->shipmentId)->toBe('abc-123')
        ->and($response->hasErrors())->toBeFalse()
        ->and($response->getLabelPdf())->toBe('fake-pdf');
});

it('detects errors in response', function () {
    $response = MailPieceCreateResponse::fromArray([
        'ShipmentID' => null,
        'PdfLabelData' => null,
        'ZplLabelText' => null,
        'Errors' => ['Invalid API key', 'Missing field'],
    ]);

    expect($response->hasErrors())->toBeTrue()
        ->and($response->errors)->toHaveCount(2);
});

it('creates track trace response from array', function () {
    $response = TrackTraceCreateResponse::fromArray([
        'TrackTraceLink' => 'https://track.example.com/abc',
        'VZCode' => 'VZ12345',
        'PdfLabelData' => base64_encode('label-pdf'),
        'ShipmentID' => 'ship-456',
        'ZplLabelText' => null,
        'ProductName' => 'Standard Parcel',
        'SortingHint' => 'A1',
        'Barcode' => '3STEST123456',
        'Errors' => null,
    ]);

    expect($response->trackTraceLink)->toBe('https://track.example.com/abc')
        ->and($response->vzCode)->toBe('VZ12345')
        ->and($response->shipmentId)->toBe('ship-456')
        ->and($response->barcode)->toBe('3STEST123456')
        ->and($response->getLabelPdf())->toBe('label-pdf');
});

it('creates cancel response', function () {
    $response = CancelResponse::fromArray(['Errors' => null]);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->hasErrors())->toBeFalse();
});

it('creates search response with shipments', function () {
    $response = SearchResponse::fromArray([
        'Shipments' => [
            [
                'ID' => 'ship-1',
                'VZCode' => 'VZ001',
                'Status' => 4,
                'StatusDescriptionEN' => 'Delivered',
                'WeightInGrams' => 500,
            ],
            [
                'ID' => 'ship-2',
                'VZCode' => 'VZ002',
                'Status' => 0,
                'StatusDescriptionEN' => 'Created',
                'WeightInGrams' => 1000,
            ],
        ],
        'Errors' => null,
    ]);

    expect($response->shipments)->toHaveCount(2)
        ->and($response->shipments[0]->id)->toBe('ship-1')
        ->and($response->shipments[0]->status)->toBe(\Budgetlens\Intrapost\Enums\ShipmentStatus::Delivered)
        ->and($response->shipments[1]->weightInGrams)->toBe(1000);
});

it('creates daily mail order response', function () {
    $response = DailyMailOrderResponse::fromArray([
        'FileName' => 'order-2024.pdf',
        'OrderCode' => 'ORD-001',
        'Base64PdfContent' => base64_encode('pdf-content'),
        'Errors' => null,
    ]);

    expect($response->fileName)->toBe('order-2024.pdf')
        ->and($response->orderCode)->toBe('ORD-001')
        ->and($response->getPdf())->toBe('pdf-content');
});

it('creates lookup address response', function () {
    $response = LookupAddressResponse::fromArray([
        'Street' => 'Keizersgracht',
        'City' => 'Amsterdam',
        'Errors' => null,
    ]);

    expect($response->street)->toBe('Keizersgracht')
        ->and($response->city)->toBe('Amsterdam');
});

it('creates product codes response', function () {
    $response = ProductCodesResponse::fromArray([
        'ProductCodes' => [
            ['ProductCode' => 'SP', 'ParcelTypeNameEng' => 'Standard Parcel'],
            ['ProductCode' => 'IP', 'ParcelTypeNameEng' => 'Insured Parcel'],
        ],
        'Errors' => null,
    ]);

    expect($response->productCodes)->toHaveCount(2)
        ->and($response->hasErrors())->toBeFalse();
});

it('creates get label response', function () {
    $response = GetLabelResponse::fromArray([
        'PdfLabelData' => base64_encode('label'),
        'ZplLabelText' => 'ZPL-CODE',
        'Errors' => null,
    ]);

    expect($response->getLabelPdf())->toBe('label')
        ->and($response->zplLabelText)->toBe('ZPL-CODE');
});

it('returns null pdf when no label data', function () {
    $response = GetLabelResponse::fromArray([
        'PdfLabelData' => null,
        'ZplLabelText' => null,
        'Errors' => null,
    ]);

    expect($response->getLabelPdf())->toBeNull();
});

it('creates mail piece order response', function () {
    $response = MailPieceOrderResponse::fromArray([
        'OrderData' => base64_encode('order-pdf'),
        'Errors' => null,
    ]);

    expect($response->getOrderPdf())->toBe('order-pdf')
        ->and($response->hasErrors())->toBeFalse();
});
