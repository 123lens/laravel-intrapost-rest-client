<?php

declare(strict_types=1);

use Budgetlens\Intrapost\DTOs\SalesChannel;
use Budgetlens\Intrapost\Enums\SalesChannelType;

it('creates sales channel', function () {
    $channel = new SalesChannel(
        type: SalesChannelType::Shopify,
        label: 'My Shop',
        identifier: 'shop-123',
    );

    expect($channel->type)->toBe(SalesChannelType::Shopify)
        ->and($channel->label)->toBe('My Shop')
        ->and($channel->identifier)->toBe('shop-123');
});

it('converts to array with enum value', function () {
    $channel = new SalesChannel(
        type: SalesChannelType::BolCom,
        identifier: 'bol-456',
    );

    $array = $channel->toArray();

    expect($array['Type'])->toBe(4)
        ->and($array['Identifier'])->toBe('bol-456')
        ->and($array)->not->toHaveKey('Label');
});

it('creates from API response', function () {
    $channel = SalesChannel::fromArray([
        'Type' => 2,
        'Label' => 'Shopify Store',
        'Identifier' => 'id-789',
    ]);

    expect($channel->type)->toBe(SalesChannelType::Shopify)
        ->and($channel->label)->toBe('Shopify Store');
});
