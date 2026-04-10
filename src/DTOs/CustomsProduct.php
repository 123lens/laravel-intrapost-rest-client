<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

class CustomsProduct
{
    public function __construct(
        public ?string $englishDescription = null,
        public ?int $quantity = null,
        public ?int $pieceWeight = null,
        public ?float $commercialValue = null,
        public ?string $countryOfOrigin = null,
        public ?string $hsCode = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'EnglishDescription' => $this->englishDescription,
            'Quantity' => $this->quantity,
            'PieceWeight' => $this->pieceWeight,
            'CommercialValue' => $this->commercialValue,
            'CountryOfOrigin' => $this->countryOfOrigin,
            'HsCode' => $this->hsCode,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            englishDescription: $data['EnglishDescription'] ?? null,
            quantity: $data['Quantity'] ?? null,
            pieceWeight: $data['PieceWeight'] ?? null,
            commercialValue: $data['CommercialValue'] ?? null,
            countryOfOrigin: $data['CountryOfOrigin'] ?? null,
            hsCode: $data['HsCode'] ?? null,
        );
    }
}
