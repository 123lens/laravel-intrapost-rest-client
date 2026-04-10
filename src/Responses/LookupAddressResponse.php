<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class LookupAddressResponse
{
    public function __construct(
        public readonly ?string $street,
        public readonly ?string $city,
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
            street: $data['Street'] ?? null,
            city: $data['City'] ?? null,
            errors: $data['Errors'] ?? [],
        );
    }
}
