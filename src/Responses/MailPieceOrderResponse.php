<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class MailPieceOrderResponse
{
    public function __construct(
        public readonly ?string $orderData,
        public readonly array $errors = [],
    ) {
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getOrderPdf(): ?string
    {
        return $this->orderData ? base64_decode($this->orderData) : null;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            orderData: $data['OrderData'] ?? null,
            errors: $data['Errors'] ?? [],
        );
    }
}
