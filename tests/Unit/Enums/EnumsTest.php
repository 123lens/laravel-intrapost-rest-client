<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Enums\CarrierType;
use Budgetlens\Intrapost\Enums\DeliveryTime;
use Budgetlens\Intrapost\Enums\LabelFormatType;
use Budgetlens\Intrapost\Enums\MailPieceProduct;
use Budgetlens\Intrapost\Enums\ShipmentStatus;
use Budgetlens\Intrapost\Enums\TrackTraceProduct;

it('has correct mail piece product values', function () {
    expect(MailPieceProduct::Standard->value)->toBe(0)
        ->and(MailPieceProduct::FixedDays->value)->toBe(3);
});

it('has correct track trace product values', function () {
    expect(TrackTraceProduct::StandardParcel->value)->toBe(1)
        ->and(TrackTraceProduct::InsuredParcel->value)->toBe(2)
        ->and(TrackTraceProduct::MailboxParcel->value)->toBe(8)
        ->and(TrackTraceProduct::StandardParcelSignature->value)->toBe(12);
});

it('has correct label format values', function () {
    expect(LabelFormatType::ZplZebra150x100->value)->toBe(2)
        ->and(LabelFormatType::Pdf150x100->value)->toBe(3);
});

it('has correct delivery time values', function () {
    expect(DeliveryTime::Standard->value)->toBe(0)
        ->and(DeliveryTime::FixedDays->value)->toBe(3);
});

it('has correct carrier type values', function () {
    expect(CarrierType::PostNL->value)->toBe(1)
        ->and(CarrierType::GLS->value)->toBe(2)
        ->and(CarrierType::DHL->value)->toBe(4);
});

it('has correct shipment status values', function () {
    expect(ShipmentStatus::Created->value)->toBe(0)
        ->and(ShipmentStatus::Delivered->value)->toBe(4)
        ->and(ShipmentStatus::DeliveryFailed->value)->toBe(13);
});

it('can create status from value', function () {
    $status = ShipmentStatus::from(4);
    expect($status)->toBe(ShipmentStatus::Delivered);
});

it('returns null for unknown status with tryFrom', function () {
    $status = ShipmentStatus::tryFrom(99);
    expect($status)->toBeNull();
});
