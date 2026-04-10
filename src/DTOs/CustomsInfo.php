<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

class CustomsInfo
{
    /**
     * @param CustomsProduct[] $products
     */
    public function __construct(
        public ?string $invoiceNumber = null,
        public array $products = [],
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'InvoiceNumber' => $this->invoiceNumber,
            'Products' => array_map(fn (CustomsProduct $p) => $p->toArray(), $this->products),
        ], fn ($value) => $value !== null && $value !== []);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            invoiceNumber: $data['InvoiceNumber'] ?? null,
            products: array_map(
                fn (array $p) => CustomsProduct::fromArray($p),
                $data['Products'] ?? []
            ),
        );
    }
}
