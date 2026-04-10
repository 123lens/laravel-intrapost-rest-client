<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders\Concerns;

use Budgetlens\Intrapost\DTOs\Option;

trait HasOptions
{
    /** @var Option[] */
    private array $options = [];

    public function option(int $type, string $value): static
    {
        $this->options[] = new Option(type: $type, value: $value);

        return $this;
    }

    public function insuredAmount(string $value): static
    {
        return $this->option(0, $value);
    }

    public function purchasingValue(string $value): static
    {
        return $this->option(1, $value);
    }

    public function commercialValue(string $value): static
    {
        return $this->option(2, $value);
    }

    public function shipmentContent(string $value): static
    {
        return $this->option(6, $value);
    }

    private function getOptionsPayload(): array
    {
        if (empty($this->options)) {
            return [];
        }

        return [
            'Options' => array_map(fn (Option $o) => $o->toArray(), $this->options),
        ];
    }
}
