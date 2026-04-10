<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\Enums\TrackTraceProduct;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\SearchResponse;

class SearchBuilder
{
    private ?string $dateFrom = null;
    private ?string $dateTill = null;
    private ?string $orderReference = null;
    private array $accountNumbers = [];
    private ?string $zipcode = null;
    private ?string $countryCode = null;
    private ?TrackTraceProduct $product = null;
    private ?string $reference = null;
    private ?string $searchString = null;
    private bool $includeHistory = false;

    public function __construct(
        private readonly IntrapostClient $client,
    ) {
    }

    public function dateFrom(string $date): static
    {
        $this->dateFrom = $date;

        return $this;
    }

    public function dateTill(string $date): static
    {
        $this->dateTill = $date;

        return $this;
    }

    public function dateRange(string $from, string $till): static
    {
        $this->dateFrom = $from;
        $this->dateTill = $till;

        return $this;
    }

    public function orderReference(string $reference): static
    {
        $this->orderReference = $reference;

        return $this;
    }

    public function accountNumbers(array $numbers): static
    {
        $this->accountNumbers = $numbers;

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

    public function product(TrackTraceProduct $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function reference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function searchString(string $search): static
    {
        $this->searchString = $search;

        return $this;
    }

    public function includeHistory(bool $include = true): static
    {
        $this->includeHistory = $include;

        return $this;
    }

    public function get(): SearchResponse
    {
        $payload = array_filter([
            'DateFrom' => $this->dateFrom,
            'DateTill' => $this->dateTill,
            'OrderReference' => $this->orderReference,
            'AccountNumbers' => !empty($this->accountNumbers) ? $this->accountNumbers : null,
            'Zipcode' => $this->zipcode,
            'CountryCode' => $this->countryCode,
            'Product' => $this->product?->value,
            'Reference' => $this->reference,
            'SearchString' => $this->searchString,
            'IncludeHistory' => $this->includeHistory ?: null,
        ], fn ($value) => $value !== null);

        $data = $this->client->post('/track-trace/search', $payload);

        return SearchResponse::fromArray($data);
    }
}
