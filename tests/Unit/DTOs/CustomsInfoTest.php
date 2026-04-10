<?php

declare(strict_types=1);

use Budgetlens\Intrapost\DTOs\CustomsInfo;
use Budgetlens\Intrapost\DTOs\CustomsProduct;

it('creates customs info with products', function () {
    $customs = new CustomsInfo(
        invoiceNumber: 'INV-2024-001',
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

    expect($customs->invoiceNumber)->toBe('INV-2024-001')
        ->and($customs->products)->toHaveCount(1)
        ->and($customs->products[0]->englishDescription)->toBe('Contact lenses');
});

it('converts to array', function () {
    $customs = new CustomsInfo(
        invoiceNumber: 'INV-001',
        products: [
            new CustomsProduct(
                englishDescription: 'Lenses',
                quantity: 5,
                pieceWeight: 30,
                commercialValue: 15.50,
                countryOfOrigin: 'NL',
            ),
        ],
    );

    $array = $customs->toArray();

    expect($array)->toHaveKey('InvoiceNumber', 'INV-001')
        ->and($array['Products'])->toHaveCount(1)
        ->and($array['Products'][0]['EnglishDescription'])->toBe('Lenses')
        ->and($array['Products'][0]['Quantity'])->toBe(5);
});

it('creates from array', function () {
    $data = [
        'InvoiceNumber' => 'INV-002',
        'Products' => [
            [
                'EnglishDescription' => 'Glasses',
                'Quantity' => 2,
                'PieceWeight' => 100,
                'CommercialValue' => 50.00,
                'CountryOfOrigin' => 'DE',
                'HsCode' => '9004.10',
            ],
        ],
    ];

    $customs = CustomsInfo::fromArray($data);

    expect($customs->invoiceNumber)->toBe('INV-002')
        ->and($customs->products)->toHaveCount(1)
        ->and($customs->products[0]->hsCode)->toBe('9004.10');
});
