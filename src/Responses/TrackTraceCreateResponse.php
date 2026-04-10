<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class TrackTraceCreateResponse
{
    public function __construct(
        public readonly ?string $trackTraceLink,
        public readonly ?string $vzCode,
        public readonly ?string $pdfLabelData,
        public readonly ?string $shipmentId,
        public readonly ?string $zplLabelText,
        public readonly ?string $productName,
        public readonly ?string $sortingHint,
        public readonly ?string $barcode = null,
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
            vzCode: $data['VZCode'] ?? null,
            pdfLabelData: $data['PdfLabelData'] ?? null,
            shipmentId: $data['ShipmentID'] ?? null,
            zplLabelText: $data['ZplLabelText'] ?? null,
            productName: $data['ProductName'] ?? null,
            sortingHint: $data['SortingHint'] ?? null,
            barcode: $data['Barcode'] ?? null,
            errors: $data['Errors'] ?? [],
        );
    }
}
