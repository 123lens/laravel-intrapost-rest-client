<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class DailyMailOrderResponse
{
    public function __construct(
        public readonly ?string $fileName,
        public readonly ?string $orderCode,
        public readonly ?string $base64PdfContent,
        public readonly array $errors = [],
    ) {
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getPdf(): ?string
    {
        return $this->base64PdfContent ? base64_decode($this->base64PdfContent) : null;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            fileName: $data['FileName'] ?? null,
            orderCode: $data['OrderCode'] ?? null,
            base64PdfContent: $data['Base64PdfContent'] ?? null,
            errors: $data['Errors'] ?? [],
        );
    }
}
