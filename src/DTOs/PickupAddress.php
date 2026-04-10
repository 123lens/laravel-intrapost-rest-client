<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

use Budgetlens\Intrapost\Enums\CarrierType;

class PickupAddress
{
    public function __construct(
        public CarrierType $carrierType,
        public string $countryCode,
        public ?string $carrierId = null,
        public ?string $company = null,
        public ?string $street = null,
        public ?int $number = null,
        public ?string $addition = null,
        public ?string $zipcode = null,
        public ?string $city = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'CarrierType' => $this->carrierType->value,
            'CarrierId' => $this->carrierId,
            'Company' => $this->company,
            'Street' => $this->street,
            'Number' => $this->number,
            'Addition' => $this->addition,
            'Zipcode' => $this->zipcode,
            'City' => $this->city,
            'CountryCode' => $this->countryCode,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            carrierType: CarrierType::from($data['CarrierType']),
            countryCode: $data['CountryCode'],
            carrierId: $data['CarrierId'] ?? null,
            company: $data['Company'] ?? null,
            street: $data['Street'] ?? null,
            number: $data['Number'] ?? null,
            addition: $data['Addition'] ?? null,
            zipcode: $data['Zipcode'] ?? null,
            city: $data['City'] ?? null,
        );
    }
}
