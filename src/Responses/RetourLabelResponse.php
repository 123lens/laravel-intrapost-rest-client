<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class RetourLabelResponse
{
    public function __construct(
        public readonly ?string $trackTraceLink,
        public readonly ?string $barcode,
        public readonly ?string $pdfLabelData,
        public readonly array $errors = [],
    ) {
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getLabelPdf(): ?string
    {
        return $this->pdfLabelData ? base64_decode($this->pdfLabelData) : null;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            trackTraceLink: $data['TrackTraceLink'] ?? null,
            barcode: $data['Barcode'] ?? null,
            pdfLabelData: $data['PdfLabelData'] ?? null,
            errors: $data['Errors'] ?? [],
        );
    }
}
