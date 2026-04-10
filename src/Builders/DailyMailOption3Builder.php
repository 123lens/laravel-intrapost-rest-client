<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\DTOs\CrateInfo;
use Budgetlens\Intrapost\Enums\DeliveryTime;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\DailyMailOrderResponse;

class DailyMailOption3Builder
{
    private ?DeliveryTime $deliveryTime = null;
    private ?CrateInfo $crateInfo = null;
    private ?string $reference = null;
    private array $amounts = [];

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

    // Letters - Netherlands
    public function letterNetherlands20Gram(int $amount): static
    {
        $this->amounts['AmountLetterNetherlands20Gram'] = $amount;

        return $this;
    }
    public function letterNetherlands50Gram(int $amount): static
    {
        $this->amounts['AmountLetterNetherlands50Gram'] = $amount;

        return $this;
    }
    public function letterNetherlands100Gram(int $amount): static
    {
        $this->amounts['AmountLetterNetherlands100Gram'] = $amount;

        return $this;
    }
    public function letterNetherlands350Gram(int $amount): static
    {
        $this->amounts['AmountLetterNetherlands350Gram'] = $amount;

        return $this;
    }
    public function letterNetherlands2000Gram(int $amount): static
    {
        $this->amounts['AmountLetterNetherlands2000Gram'] = $amount;

        return $this;
    }

    // Letters - Europe
    public function letterEurope20Gram(int $amount): static
    {
        $this->amounts['AmountLetterEurope20Gram'] = $amount;

        return $this;
    }
    public function letterEurope50Gram(int $amount): static
    {
        $this->amounts['AmountLetterEurope50Gram'] = $amount;

        return $this;
    }
    public function letterEurope100Gram(int $amount): static
    {
        $this->amounts['AmountLetterEurope100Gram'] = $amount;

        return $this;
    }
    public function letterEurope350Gram(int $amount): static
    {
        $this->amounts['AmountLetterEurope350Gram'] = $amount;

        return $this;
    }
    public function letterEurope2000Gram(int $amount): static
    {
        $this->amounts['AmountLetterEurope2000Gram'] = $amount;

        return $this;
    }

    // Letters - Rest of World
    public function letterRestOfWorld20Gram(int $amount): static
    {
        $this->amounts['AmountLetterRestOfWorld20Gram'] = $amount;

        return $this;
    }
    public function letterRestOfWorld50Gram(int $amount): static
    {
        $this->amounts['AmountLetterRestOfWorld50Gram'] = $amount;

        return $this;
    }
    public function letterRestOfWorld100Gram(int $amount): static
    {
        $this->amounts['AmountLetterRestOfWorld100Gram'] = $amount;

        return $this;
    }
    public function letterRestOfWorld350Gram(int $amount): static
    {
        $this->amounts['AmountLetterRestOfWorld350Gram'] = $amount;

        return $this;
    }
    public function letterRestOfWorld2000Gram(int $amount): static
    {
        $this->amounts['AmountLetterRestOfWorld2000Gram'] = $amount;

        return $this;
    }

    // Registered letters
    public function registeredLetterNetherlands(int $amount): static
    {
        $this->amounts['AmountRegisteredLetterNetherlands'] = $amount;

        return $this;
    }
    public function registeredLetterEurope(int $amount): static
    {
        $this->amounts['AmountRegisteredLetterEurope'] = $amount;

        return $this;
    }
    public function registeredLetterRestOfWorld(int $amount): static
    {
        $this->amounts['AmountRegisteredLetterRestOfWorld'] = $amount;

        return $this;
    }

    // Insured letters
    public function insuredLetterNetherlands(int $amount): static
    {
        $this->amounts['AmountInsuredLetterNetherlands'] = $amount;

        return $this;
    }
    public function insuredLetterEurope(int $amount): static
    {
        $this->amounts['AmountInsuredLetterEurope'] = $amount;

        return $this;
    }
    public function insuredLetterRestOfWorld(int $amount): static
    {
        $this->amounts['AmountInsuredLetterRestOfWorld'] = $amount;

        return $this;
    }

    // Parcels - Netherlands
    public function parcelNetherlands2Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelNetherlands2Kilogram'] = $amount;

        return $this;
    }
    public function parcelNetherlands5Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelNetherlands5Kilogram'] = $amount;

        return $this;
    }
    public function parcelNetherlands10Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelNetherlands10Kilogram'] = $amount;

        return $this;
    }
    public function parcelNetherlands20Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelNetherlands20Kilogram'] = $amount;

        return $this;
    }
    public function parcelNetherlands30Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelNetherlands30Kilogram'] = $amount;

        return $this;
    }

    // Parcels - Europe
    public function parcelEurope2Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelEurope2Kilogram'] = $amount;

        return $this;
    }
    public function parcelEurope5Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelEurope5Kilogram'] = $amount;

        return $this;
    }
    public function parcelEurope10Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelEurope10Kilogram'] = $amount;

        return $this;
    }
    public function parcelEurope20Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelEurope20Kilogram'] = $amount;

        return $this;
    }
    public function parcelEurope30Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelEurope30Kilogram'] = $amount;

        return $this;
    }

    // Parcels - Rest of World
    public function parcelRestOfWorld2Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelRestOfWorld2Kilogram'] = $amount;

        return $this;
    }
    public function parcelRestOfWorld5Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelRestOfWorld5Kilogram'] = $amount;

        return $this;
    }
    public function parcelRestOfWorld10Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelRestOfWorld10Kilogram'] = $amount;

        return $this;
    }
    public function parcelRestOfWorld20Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelRestOfWorld20Kilogram'] = $amount;

        return $this;
    }
    public function parcelRestOfWorld30Kilogram(int $amount): static
    {
        $this->amounts['AmountParcelRestOfWorld30Kilogram'] = $amount;

        return $this;
    }

    // Registered parcels
    public function registeredParcelNetherlands(int $amount): static
    {
        $this->amounts['AmountRegisteredParcelNetherlands'] = $amount;

        return $this;
    }
    public function registeredParcelEurope(int $amount): static
    {
        $this->amounts['AmountRegisteredParcelEurope'] = $amount;

        return $this;
    }
    public function registeredParcelRestOfWorld(int $amount): static
    {
        $this->amounts['AmountRegisteredParcelRestOfWorld'] = $amount;

        return $this;
    }

    // Insured parcels
    public function insuredParcelNetherlands(int $amount): static
    {
        $this->amounts['AmountInsuredParcelNetherlands'] = $amount;

        return $this;
    }
    public function insuredParcelEurope(int $amount): static
    {
        $this->amounts['AmountInsuredParcelEurope'] = $amount;

        return $this;
    }
    public function insuredParcelRestOfWorld(int $amount): static
    {
        $this->amounts['AmountInsuredParcelRestOfWorld'] = $amount;

        return $this;
    }

    public function send(): DailyMailOrderResponse
    {
        $payload = array_filter([
            'AccountNumber' => $this->client->getAccountNumber(),
            'DeliveryTime' => $this->deliveryTime?->value,
            'Reference' => $this->reference,
        ], fn ($value) => $value !== null);

        if ($this->crateInfo) {
            $payload['CrateInfo'] = $this->crateInfo->toArray();
        }

        $payload = array_merge($payload, $this->amounts);

        $data = $this->client->post('/order/create-daily-mail-option-3', $payload);

        return DailyMailOrderResponse::fromArray($data);
    }
}
