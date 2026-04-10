<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class ProductCodesResponse
{
    public function __construct(
        public readonly array $productCodes = [],
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
            productCodes: $data['ProductCodes'] ?? [],
            errors: $data['Errors'] ?? [],
        );
    }
}
