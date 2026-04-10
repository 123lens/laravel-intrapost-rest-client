<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\Builders\Concerns\HasAddress;
use Budgetlens\Intrapost\Builders\Concerns\HasLabelFormat;
use Budgetlens\Intrapost\Builders\Concerns\HasOptions;
use Budgetlens\Intrapost\Builders\Concerns\HasSalesChannel;
use Budgetlens\Intrapost\Enums\MailPieceProduct;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\MailPieceCreateResponse;

class MailPieceBuilder
{
    use HasAddress;
    use HasLabelFormat;
    use HasOptions;
    use HasSalesChannel;

    private ?MailPieceProduct $product = null;
    private ?int $weightGrams = null;
    private ?string $reference = null;
    private ?string $labelReference1 = null;
    private ?string $labelReference2 = null;
    private ?string $orderReference = null;
    private ?string $bolComOrderId = null;

    public function __construct(
        private readonly IntrapostClient $client,
    ) {
    }

    public function product(MailPieceProduct $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function standard(): static
    {
        return $this->product(MailPieceProduct::Standard);
    }

    public function fixedDays(): static
    {
        return $this->product(MailPieceProduct::FixedDays);
    }

    public function weight(int $grams): static
    {
        $this->weightGrams = $grams;

        return $this;
    }

    public function reference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function labelReference1(string $reference): static
    {
        $this->labelReference1 = $reference;

        return $this;
    }

    public function labelReference2(string $reference): static
    {
        $this->labelReference2 = $reference;

        return $this;
    }

    public function orderReference(string $reference): static
    {
        $this->orderReference = $reference;

        return $this;
    }

    public function bolComOrderId(string $orderId): static
    {
        $this->bolComOrderId = $orderId;

        return $this;
    }

    public function dimensions(int $lengthMm, int $widthMm, int $heightMm): static
    {
        $this->option(3, (string) $lengthMm);
        $this->option(4, (string) $widthMm);
        $this->option(5, (string) $heightMm);

        return $this;
    }

    public function projectCode(string $code): static
    {
        return $this->option(7, $code);
    }

    public function send(): MailPieceCreateResponse
    {
        $payload = array_filter([
            'AccountNumber' => $this->client->getAccountNumber(),
            'Product' => $this->product?->value,
            'WeightGrams' => $this->weightGrams,
            'Reference' => $this->reference,
            'LabelReference1' => $this->labelReference1,
            'LabelReference2' => $this->labelReference2,
            'OrderReference' => $this->orderReference,
            'BolComOrderId' => $this->bolComOrderId,
        ], fn ($value) => $value !== null);

        $payload = array_merge(
            $payload,
            $this->getAddressPayload(),
            $this->getLabelFormatPayload(),
            $this->getOptionsPayload(),
            $this->getSalesChannelPayload(),
        );

        $data = $this->client->post('/mail-piece/create', $payload);

        return MailPieceCreateResponse::fromArray($data);
    }
}
