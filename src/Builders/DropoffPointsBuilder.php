<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\PickupPointsResponse;

class DropoffPointsBuilder
{
    private ?int $limit = null;
    private ?string $street = null;
    private ?int $number = null;
    private ?string $zipcode = null;
    private ?string $countryCode = null;

    public function __construct(
        private readonly IntrapostClient $client,
    ) {
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

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

    public function zipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function countryCode(string $code): static
    {
        $this->countryCode = $code;

        return $this;
    }

    public function get(): PickupPointsResponse
    {
        $payload = array_filter([
            'AccountNumber' => $this->client->getAccountNumber(),
            'Limit' => $this->limit,
            'Street' => $this->street,
            'Number' => $this->number,
            'Zipcode' => $this->zipcode,
            'Countrycode' => $this->countryCode,
        ], fn ($value) => $value !== null);

        $data = $this->client->post('/utility/dropoffpoints-for-international-address', $payload);

        return PickupPointsResponse::fromArray($data);
    }
}
