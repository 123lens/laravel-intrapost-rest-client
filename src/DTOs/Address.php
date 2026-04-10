<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\DTOs;

class Address
{
    public function __construct(
        public string $street = '',
        public ?int $number = null,
        public ?string $addition = null,
        public string $zipcode = '',
        public string $city = '',
        public string $countryCode = '',
        public ?string $company = null,
        public string $fullName = '',
        public ?string $email = null,
        public ?string $phone = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'Street' => $this->street,
            'Number' => $this->number,
            'Addition' => $this->addition,
            'Zipcode' => $this->zipcode,
            'City' => $this->city,
            'CountryCode' => $this->countryCode,
            'Company' => $this->company,
            'FullName' => $this->fullName,
            'Email' => $this->email,
            'Phone' => $this->phone,
        ], fn ($value) => $value !== null && $value !== '');
    }

    public static function fromArray(array $data): self
    {
        return new self(
            street: $data['Street'] ?? '',
            number: $data['Number'] ?? null,
            addition: $data['Addition'] ?? null,
            zipcode: $data['Zipcode'] ?? '',
            city: $data['City'] ?? '',
            countryCode: $data['CountryCode'] ?? '',
            company: $data['Company'] ?? null,
            fullName: $data['FullName'] ?? '',
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
        );
    }
}
