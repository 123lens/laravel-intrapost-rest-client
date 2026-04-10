<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

class GeoLocation
{
    public function __construct(
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            latitude: $data['Latitude'] ?? null,
            longitude: $data['Longitude'] ?? null,
        );
    }
}
