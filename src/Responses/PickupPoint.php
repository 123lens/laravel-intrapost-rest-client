<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

use Budgetlens\Intrapost\DTOs\GeoLocation;

class PickupPoint
{
    public function __construct(
        public readonly ?string $carrierId,
        public readonly ?string $name,
        public readonly ?string $street,
        public readonly ?int $number,
        public readonly ?string $addition,
        public readonly ?string $zipcode,
        public readonly ?string $city,
        public readonly ?string $countryCode,
        public readonly ?int $distance,
        public readonly ?GeoLocation $geoLocation,
        public readonly array $openingHours = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            carrierId: $data['CarrierId'] ?? null,
            name: $data['Name'] ?? null,
            street: $data['Street'] ?? null,
            number: $data['Number'] ?? null,
            addition: $data['Addition'] ?? null,
            zipcode: $data['Zipcode'] ?? null,
            city: $data['City'] ?? null,
            countryCode: $data['CountryCode'] ?? null,
            distance: $data['Distance'] ?? null,
            geoLocation: isset($data['GeoLocation']) ? GeoLocation::fromArray($data['GeoLocation']) : null,
            openingHours: $data['OpeningHours'] ?? [],
        );
    }
}
