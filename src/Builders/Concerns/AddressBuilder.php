<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders\Concerns;

use Budgetlens\Intrapost\DTOs\Address;

class AddressBuilder
{
    private string $street = '';
    private ?int $number = null;
    private ?string $addition = null;
    private string $zipcode = '';
    private string $city = '';
    private string $countryCode = '';
    private ?string $company = null;
    private string $fullName = '';
    private ?string $email = null;
    private ?string $phone = null;

    public function street(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function number(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function addition(string $addition): static
    {
        $this->addition = $addition;

        return $this;
    }

    public function zipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function city(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function countryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function company(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function fullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function email(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function phone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function build(): Address
    {
        return new Address(
            street: $this->street,
            number: $this->number,
            addition: $this->addition,
            zipcode: $this->zipcode,
            city: $this->city,
            countryCode: $this->countryCode,
            company: $this->company,
            fullName: $this->fullName,
            email: $this->email,
            phone: $this->phone,
        );
    }
}
