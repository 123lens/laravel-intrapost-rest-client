<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

class CrateInfo
{
    public function __construct(
        public ?int $amount = null,
        public ?string $description = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'Amount' => $this->amount,
            'Description' => $this->description,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['Amount'] ?? null,
            description: $data['Description'] ?? null,
        );
    }
}
