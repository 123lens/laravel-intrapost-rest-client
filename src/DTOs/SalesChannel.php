<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

use Budgetlens\Intrapost\Enums\SalesChannelType;

class SalesChannel
{
    public function __construct(
        public ?SalesChannelType $type = null,
        public ?string $label = null,
        public ?string $identifier = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'Type' => $this->type?->value,
            'Label' => $this->label,
            'Identifier' => $this->identifier,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            type: isset($data['Type']) ? SalesChannelType::from($data['Type']) : null,
            label: $data['Label'] ?? null,
            identifier: $data['Identifier'] ?? null,
        );
    }
}
