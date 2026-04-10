<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

class Option
{
    public function __construct(
        public int $type,
        public string $value,
    ) {
    }

    public function toArray(): array
    {
        return [
            'Type' => $this->type,
            'Value' => $this->value,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['Type'],
            value: $data['Value'],
        );
    }
}
