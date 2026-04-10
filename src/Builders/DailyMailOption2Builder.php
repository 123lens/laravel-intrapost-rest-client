<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\DTOs\CrateInfo;
use Budgetlens\Intrapost\Enums\DeliveryTime;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\DailyMailOrderResponse;

class DailyMailOption2Builder
{
    private ?DeliveryTime $deliveryTime = null;
    private ?CrateInfo $crateInfo = null;
    private ?string $reference = null;
    private ?int $amountNetherlands = null;
    private ?int $amountInternational = null;
    private ?int $amountSpecialLetters = null;
    private ?int $amountTrackTrace = null;

    public function __construct(
        private readonly IntrapostClient $client,
    ) {
    }

    public function deliveryTime(DeliveryTime $time): static
    {
        $this->deliveryTime = $time;

        return $this;
    }

    public function standard(): static
    {
        return $this->deliveryTime(DeliveryTime::Standard);
    }

    public function fixedDays(): static
    {
        return $this->deliveryTime(DeliveryTime::FixedDays);
    }

    public function crate(int $amount, ?string $description = null): static
    {
        $this->crateInfo = new CrateInfo(amount: $amount, description: $description);

        return $this;
    }

    public function reference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function amountNetherlands(int $amount): static
    {
        $this->amountNetherlands = $amount;

        return $this;
    }

    public function amountInternational(int $amount): static
    {
        $this->amountInternational = $amount;

        return $this;
    }

    public function amountSpecialLetters(int $amount): static
    {
        $this->amountSpecialLetters = $amount;

        return $this;
    }

    public function amountTrackTrace(int $amount): static
    {
        $this->amountTrackTrace = $amount;

        return $this;
    }

    public function send(): DailyMailOrderResponse
    {
        $payload = array_filter([
            'AccountNumber' => $this->client->getAccountNumber(),
            'DeliveryTime' => $this->deliveryTime?->value,
            'Reference' => $this->reference,
            'AmountNetherlands' => $this->amountNetherlands,
            'AmountInternational' => $this->amountInternational,
            'AmountSpecialLetters' => $this->amountSpecialLetters,
            'AmountTrackTrace' => $this->amountTrackTrace,
        ], fn ($value) => $value !== null);

        if ($this->crateInfo) {
            $payload['CrateInfo'] = $this->crateInfo->toArray();
        }

        $data = $this->client->post('/order/create-daily-mail-option-2', $payload);

        return DailyMailOrderResponse::fromArray($data);
    }
}
