<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class CancelResponse
{
    public function __construct(
        public readonly array $errors = [],
    ) {
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function isSuccessful(): bool
    {
        return !$this->hasErrors();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            errors: $data['Errors'] ?? [],
        );
    }
}
