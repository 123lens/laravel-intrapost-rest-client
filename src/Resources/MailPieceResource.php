<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Resources;

use Budgetlens\Intrapost\Builders\MailPieceBuilder;
use Budgetlens\Intrapost\Responses\GetLabelResponse;
use Budgetlens\Intrapost\Responses\MailPieceOrderResponse;

class MailPieceResource extends AbstractResource
{
    public function create(): MailPieceBuilder
    {
        return new MailPieceBuilder($this->client);
    }

    public function order(string $orderReference): MailPieceOrderResponse
    {
        $data = $this->client->post('/mail-piece/order', [
            'AccountNumber' => $this->client->getAccountNumber(),
            'OrderReference' => $orderReference,
        ]);

        return MailPieceOrderResponse::fromArray($data);
    }

    public function getLabel(string $shipmentId): GetLabelResponse
    {
        $data = $this->client->post('/mail-piece/get-label', [
            'AccountNumber' => $this->client->getAccountNumber(),
            'ShipmentID' => $shipmentId,
        ]);

        return GetLabelResponse::fromArray($data);
    }
}
