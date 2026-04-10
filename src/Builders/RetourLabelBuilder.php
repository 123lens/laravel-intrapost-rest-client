<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\Enums\TrackTraceProduct;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\RetourLabelResponse;

class RetourLabelBuilder
{
    private ?TrackTraceProduct $product = null;
    private ?string $shipmentId = null;
    private ?string $vzCode = null;

    public function __construct(
        private readonly IntrapostClient $client,
    ) {
    }

    public function product(TrackTraceProduct $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function shipmentId(string $id): static
    {
        $this->shipmentId = $id;

        return $this;
    }

    public function vzCode(string $code): static
    {
        $this->vzCode = $code;

        return $this;
    }

    public function send(): RetourLabelResponse
    {
        $payload = array_filter([
            'AccountNumber' => $this->client->getAccountNumber(),
            'Product' => $this->product?->value,
            'ShipmentID' => $this->shipmentId,
            'VZCode' => $this->vzCode,
        ], fn ($value) => $value !== null);

        $data = $this->client->post('/track-trace/get-retour-label', $payload);

        return RetourLabelResponse::fromArray($data);
    }
}
