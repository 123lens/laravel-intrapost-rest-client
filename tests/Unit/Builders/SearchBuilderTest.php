<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Enums\TrackTraceProduct;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('searches shipments by date range', function () {
    $client = MockClient::create([
        [
            'Shipments' => [
                ['ID' => 'S-001', 'Status' => 4, 'VZCode' => 'VZ001'],
            ],
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->search()
        ->dateRange('2024-01-01', '2024-01-31')
        ->includeHistory()
        ->get();

    expect($response->shipments)->toHaveCount(1)
        ->and($response->shipments[0]->id)->toBe('S-001');
});

it('searches by order reference', function () {
    $client = MockClient::create([
        [
            'Shipments' => [],
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->search()
        ->orderReference('ORDER-123')
        ->get();

    expect($response->shipments)->toBeEmpty()
        ->and($response->hasErrors())->toBeFalse();
});

it('searches with all filters', function () {
    $client = MockClient::create([
        [
            'Shipments' => [
                ['ID' => 'S-010', 'Status' => 0],
            ],
            'Errors' => null,
        ],
    ]);

    $response = $client->trackTrace()
        ->search()
        ->dateFrom('2024-03-01')
        ->dateTill('2024-03-31')
        ->zipcode('1234AB')
        ->countryCode('NL')
        ->product(TrackTraceProduct::StandardParcel)
        ->reference('REF-SEARCH')
        ->searchString('test')
        ->accountNumbers(['ACC-1', 'ACC-2'])
        ->get();

    expect($response->shipments)->toHaveCount(1);
});
