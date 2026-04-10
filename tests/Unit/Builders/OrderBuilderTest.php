<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Enums\DeliveryTime;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;

it('creates daily mail order option 1', function () {
    $client = MockClient::create([
        [
            'FileName' => 'order.pdf',
            'OrderCode' => 'ORD-001',
            'Base64PdfContent' => base64_encode('pdf'),
            'Errors' => null,
        ],
    ]);

    $response = $client->order()
        ->createDailyMailOption1()
        ->standard()
        ->crate(5, 'Standard crate')
        ->reference('DAILY-001')
        ->send();

    expect($response->orderCode)->toBe('ORD-001')
        ->and($response->fileName)->toBe('order.pdf')
        ->and($response->getPdf())->toBe('pdf');
});

it('creates daily mail order option 2 with amounts', function () {
    $client = MockClient::create([
        [
            'FileName' => 'order2.pdf',
            'OrderCode' => 'ORD-002',
            'Base64PdfContent' => base64_encode('pdf2'),
            'Errors' => null,
        ],
    ]);

    $response = $client->order()
        ->createDailyMailOption2()
        ->fixedDays()
        ->amountNetherlands(100)
        ->amountInternational(25)
        ->amountSpecialLetters(10)
        ->amountTrackTrace(5)
        ->crate(3)
        ->reference('DAILY-002')
        ->send();

    expect($response->orderCode)->toBe('ORD-002');
});

it('creates daily mail order option 3 with detailed amounts', function () {
    $client = MockClient::create([
        [
            'FileName' => 'order3.pdf',
            'OrderCode' => 'ORD-003',
            'Base64PdfContent' => base64_encode('pdf3'),
            'Errors' => null,
        ],
    ]);

    $response = $client->order()
        ->createDailyMailOption3()
        ->deliveryTime(DeliveryTime::Standard)
        ->letterNetherlands20Gram(50)
        ->letterNetherlands50Gram(30)
        ->letterEurope20Gram(10)
        ->parcelNetherlands2Kilogram(5)
        ->registeredLetterNetherlands(2)
        ->insuredParcelNetherlands(1)
        ->crate(2, 'Mixed')
        ->send();

    expect($response->orderCode)->toBe('ORD-003');
});
