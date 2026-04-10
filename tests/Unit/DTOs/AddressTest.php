<?php

declare(strict_types=1);

use Budgetlens\Intrapost\DTOs\Address;

it('creates an address from constructor', function () {
    $address = new Address(
        street: 'Hoofdstraat',
        number: 1,
        addition: 'A',
        zipcode: '1234AB',
        city: 'Amsterdam',
        countryCode: 'NL',
        company: 'Test BV',
        fullName: 'Jan Jansen',
        email: 'jan@test.nl',
        phone: '0612345678',
    );

    expect($address->street)->toBe('Hoofdstraat')
        ->and($address->number)->toBe(1)
        ->and($address->addition)->toBe('A')
        ->and($address->zipcode)->toBe('1234AB')
        ->and($address->city)->toBe('Amsterdam')
        ->and($address->countryCode)->toBe('NL')
        ->and($address->company)->toBe('Test BV')
        ->and($address->fullName)->toBe('Jan Jansen')
        ->and($address->email)->toBe('jan@test.nl')
        ->and($address->phone)->toBe('0612345678');
});

it('converts to array with PascalCase keys', function () {
    $address = new Address(
        street: 'Hoofdstraat',
        number: 1,
        zipcode: '1234AB',
        city: 'Amsterdam',
        countryCode: 'NL',
        fullName: 'Jan Jansen',
    );

    $array = $address->toArray();

    expect($array)->toBe([
        'Street' => 'Hoofdstraat',
        'Number' => 1,
        'Zipcode' => '1234AB',
        'City' => 'Amsterdam',
        'CountryCode' => 'NL',
        'FullName' => 'Jan Jansen',
    ]);
});

it('omits null and empty values from array', function () {
    $address = new Address(
        street: 'Hoofdstraat',
        zipcode: '1234AB',
        city: 'Amsterdam',
        countryCode: 'NL',
        fullName: 'Jan Jansen',
    );

    $array = $address->toArray();

    expect($array)->not->toHaveKey('Number')
        ->and($array)->not->toHaveKey('Addition')
        ->and($array)->not->toHaveKey('Company')
        ->and($array)->not->toHaveKey('Email')
        ->and($array)->not->toHaveKey('Phone');
});

it('creates from API response array', function () {
    $data = [
        'Street' => 'Keizersgracht',
        'Number' => 100,
        'Addition' => 'B',
        'Zipcode' => '1015AA',
        'City' => 'Amsterdam',
        'CountryCode' => 'NL',
        'Company' => 'Acme BV',
        'FullName' => 'Piet Pietersen',
        'Email' => 'piet@acme.nl',
        'Phone' => '0201234567',
    ];

    $address = Address::fromArray($data);

    expect($address->street)->toBe('Keizersgracht')
        ->and($address->number)->toBe(100)
        ->and($address->addition)->toBe('B')
        ->and($address->company)->toBe('Acme BV');
});

it('handles missing fields in fromArray gracefully', function () {
    $address = Address::fromArray(['Street' => 'Test']);

    expect($address->street)->toBe('Test')
        ->and($address->number)->toBeNull()
        ->and($address->city)->toBe('')
        ->and($address->fullName)->toBe('');
});
