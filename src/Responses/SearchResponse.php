<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class SearchResponse
{
    /**
     * @param Shipment[] $shipments
     */
    public function __construct(
        public readonly array $shipments = [],
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
            shipments: array_map(
                fn (array $s) => Shipment::fromArray($s),
                $data['Shipments'] ?? []
            ),
            errors: $data['Errors'] ?? [],
        );
    }
}
