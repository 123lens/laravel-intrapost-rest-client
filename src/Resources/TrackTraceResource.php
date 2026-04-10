<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Resources;

use Budgetlens\Intrapost\Builders\MailboxParcelBuilder;
use Budgetlens\Intrapost\Builders\RetourLabelBuilder;
use Budgetlens\Intrapost\Builders\SearchBuilder;
use Budgetlens\Intrapost\Builders\TrackTraceBuilder;
use Budgetlens\Intrapost\Enums\LabelFormatType;
use Budgetlens\Intrapost\Responses\CancelResponse;
use Budgetlens\Intrapost\Responses\GetLabelResponse;
use Budgetlens\Intrapost\Responses\SearchResponse;

class TrackTraceResource extends AbstractResource
{
    public function create(): TrackTraceBuilder
    {
        return new TrackTraceBuilder($this->client);
    }

    public function createMailboxParcel(): MailboxParcelBuilder
    {
        return new MailboxParcelBuilder($this->client);
    }

    public function getRetourLabel(): RetourLabelBuilder
    {
        return new RetourLabelBuilder($this->client);
    }

    public function cancel(string $shipmentId): CancelResponse
    {
        $data = $this->client->post('/track-trace/cancel', [
            'ShipmentID' => $shipmentId,
        ]);

        return CancelResponse::fromArray($data);
    }

    public function search(): SearchBuilder
    {
        return new SearchBuilder($this->client);
    }

    /**
     * @param string[] $pieceIds
     */
    public function getFromId(array $pieceIds, bool $includeHistory = false): SearchResponse
    {
        $data = $this->client->post('/track-trace/get-from-id', [
            'PieceIDs' => $pieceIds,
            'IncludeHistory' => $includeHistory,
        ]);

        return SearchResponse::fromArray($data);
    }

    /**
     * @param string[] $vzCodes
     */
    public function getFromVz(array $vzCodes, bool $includeHistory = false): SearchResponse
    {
        $data = $this->client->post('/track-trace/get-from-vz', [
            'VZCodes' => $vzCodes,
            'IncludeHistory' => $includeHistory,
        ]);

        return SearchResponse::fromArray($data);
    }

    /**
     * @param string[] $shipmentIds
     */
    public function createLabels(array $shipmentIds, LabelFormatType $format): GetLabelResponse
    {
        $data = $this->client->post('/track-trace/create-labels', [
            'ShipmentIDs' => $shipmentIds,
            'LabelFormatType' => $format->value,
        ]);

        return GetLabelResponse::fromArray($data);
    }
}
