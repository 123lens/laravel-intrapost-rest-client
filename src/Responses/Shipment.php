<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

use Budgetlens\Intrapost\DTOs\Address;
use Budgetlens\Intrapost\Enums\ShipmentStatus;

class Shipment
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $vzCode,
        public readonly ?int $parcelType,
        public readonly ?Address $address,
        public readonly ?string $accountNumber,
        public readonly ?string $trackTraceLink,
        public readonly ?string $externalLink,
        public readonly ?string $barcode,
        public readonly ?string $reference,
        public readonly ?int $weightInGrams,
        public readonly ?float $weightInKilograms,
        public readonly ?ShipmentStatus $status,
        public readonly ?string $statusTimestamp,
        public readonly ?string $statusDescriptionNL,
        public readonly ?string $statusDescriptionEN,
        public readonly ?string $deliveryFrom,
        public readonly ?string $deliveryTill,
        public readonly ?string $pickupAddressLines,
        public readonly array $statusHistory = [],
        public readonly array $specials = [],
        public readonly array $measurements = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'] ?? null,
            vzCode: $data['VZCode'] ?? null,
            parcelType: $data['ParcelType'] ?? null,
            address: isset($data['Address']) ? Address::fromArray($data['Address']) : null,
            accountNumber: $data['AccountNumber'] ?? null,
            trackTraceLink: $data['TrackTraceLink'] ?? null,
            externalLink: $data['ExternalLink'] ?? null,
            barcode: $data['Barcode'] ?? null,
            reference: $data['Reference'] ?? null,
            weightInGrams: $data['WeightInGrams'] ?? null,
            weightInKilograms: isset($data['WeightInKilograms']) ? (float) $data['WeightInKilograms'] : null,
            status: isset($data['Status']) ? ShipmentStatus::tryFrom($data['Status']) : null,
            statusTimestamp: $data['StatusTimestamp'] ?? null,
            statusDescriptionNL: $data['StatusDescriptionNL'] ?? null,
            statusDescriptionEN: $data['StatusDescriptionEN'] ?? null,
            deliveryFrom: $data['DeliveryFrom'] ?? null,
            deliveryTill: $data['DeliveryTill'] ?? null,
            pickupAddressLines: $data['PickupAddressLines'] ?? null,
            statusHistory: $data['StatusHistory'] ?? [],
            specials: $data['Specials'] ?? [],
            measurements: $data['Measurements'] ?? [],
        );
    }
}
