<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders\Concerns;

use Budgetlens\Intrapost\Enums\LabelFormatType;

trait HasLabelFormat
{
    private ?LabelFormatType $labelFormatType = null;

    public function labelFormat(LabelFormatType $format): static
    {
        $this->labelFormatType = $format;

        return $this;
    }

    public function labelAsPdf(): static
    {
        $this->labelFormatType = LabelFormatType::Pdf150x100;

        return $this;
    }

    public function labelAsZpl(): static
    {
        $this->labelFormatType = LabelFormatType::ZplZebra150x100;

        return $this;
    }

    private function getLabelFormatPayload(): array
    {
        if ($this->labelFormatType) {
            return ['LabelFormatType' => $this->labelFormatType->value];
        }

        return [];
    }
}
