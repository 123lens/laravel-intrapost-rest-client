<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders\Concerns;

use Budgetlens\Intrapost\DTOs\SalesChannel;
use Budgetlens\Intrapost\Enums\SalesChannelType;

trait HasSalesChannel
{
    private ?SalesChannel $salesChannel = null;

    public function salesChannel(SalesChannelType $type, ?string $label = null, ?string $identifier = null): static
    {
        $this->salesChannel = new SalesChannel(
            type: $type,
            label: $label,
            identifier: $identifier,
        );

        return $this;
    }

    private function getSalesChannelPayload(): array
    {
        if ($this->salesChannel) {
            return ['SalesChannel' => $this->salesChannel->toArray()];
        }

        return [];
    }
}
