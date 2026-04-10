<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders\Concerns;

use Budgetlens\Intrapost\DTOs\Address;
use Closure;

trait HasAddress
{
    private ?Address $receiverAddress = null;
    private ?Address $returnAddress = null;

    public function receiver(Address|Closure $address): static
    {
        if ($address instanceof Closure) {
            $builder = new AddressBuilder();
            $address($builder);
            $this->receiverAddress = $builder->build();
        } else {
            $this->receiverAddress = $address;
        }

        return $this;
    }

    public function returnAddress(Address|Closure $address): static
    {
        if ($address instanceof Closure) {
            $builder = new AddressBuilder();
            $address($builder);
            $this->returnAddress = $builder->build();
        } else {
            $this->returnAddress = $address;
        }

        return $this;
    }

    private function getAddressPayload(): array
    {
        $payload = [];

        if ($this->receiverAddress) {
            $payload['ReceiverAddress'] = $this->receiverAddress->toArray();
        }

        if ($this->returnAddress) {
            $payload['ReturnAddress'] = $this->returnAddress->toArray();
        }

        return $payload;
    }
}
