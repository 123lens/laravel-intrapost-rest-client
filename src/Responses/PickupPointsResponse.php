<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

use Budgetlens\Intrapost\Enums\CarrierType;

class PickupPointsResponse
{
    /**
     * @param PickupPoint[] $pickupPoints
     */
    public function __construct(
        public readonly ?CarrierType $carrierType,
        public readonly array $pickupPoints = [],
        public readonly array $errors = [],
    ) {
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            carrierType: isset($data['CarrierType']) ? CarrierType::tryFrom($data['CarrierType']) : null,
            pickupPoints: array_map(
                fn (array $p) => PickupPoint::fromArray($p),
                $data['PickUpPoints'] ?? []
            ),
            errors: $data['Errors'] ?? [],
        );
    }
}
