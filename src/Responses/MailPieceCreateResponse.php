<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Responses;

class MailPieceCreateResponse
{
    public function __construct(
        public readonly ?string $shipmentId,
        public readonly ?string $pdfLabelData,
        public readonly ?string $zplLabelText,
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
            shipmentId: $data['ShipmentID'] ?? null,
            pdfLabelData: $data['PdfLabelData'] ?? null,
            zplLabelText: $data['ZplLabelText'] ?? null,
            errors: $data['Errors'] ?? [],
        );
    }
}
