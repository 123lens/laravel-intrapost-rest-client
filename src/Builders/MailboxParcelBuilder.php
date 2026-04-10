<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\Builders\Concerns\HasAddress;
use Budgetlens\Intrapost\Builders\Concerns\HasLabelFormat;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\TrackTraceCreateResponse;

class MailboxParcelBuilder
{
    use HasAddress;
    use HasLabelFormat;

    private ?int $weightGrams = null;
    private ?string $reference = null;
    private ?string $orderReference = null;

    public function __construct(
        private readonly IntrapostClient $client,
    ) {
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

    public function orderReference(string $reference): static
    {
        $this->orderReference = $reference;

        return $this;
    }

    public function send(): TrackTraceCreateResponse
    {
        $payload = array_filter([
            'AccountNumber' => $this->client->getAccountNumber(),
            'WeightGrams' => $this->weightGrams,
            'Reference' => $this->reference,
            'OrderReference' => $this->orderReference,
        ], fn ($value) => $value !== null);

        $payload = array_merge(
            $payload,
            $this->getAddressPayload(),
            $this->getLabelFormatPayload(),
        );

        $data = $this->client->post('/track-trace/create_mailboxparcel', $payload);

        return TrackTraceCreateResponse::fromArray($data);
    }
}
