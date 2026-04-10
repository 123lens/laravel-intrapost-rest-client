<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Resources;

use Budgetlens\Intrapost\Builders\DropoffPointsBuilder;
use Budgetlens\Intrapost\Builders\PickupPointsBuilder;
use Budgetlens\Intrapost\Responses\LookupAddressResponse;
use Budgetlens\Intrapost\Responses\ProductCodesResponse;

class UtilityResource extends AbstractResource
{
    public function lookupAddress(string $zipcode, int $number): LookupAddressResponse
    {
        $data = $this->client->post('/utility/lookup-address', [
            'Zipcode' => $zipcode,
            'Number' => $number,
        ]);

        return LookupAddressResponse::fromArray($data);
    }

    public function productCodes(): ProductCodesResponse
    {
        $data = $this->client->post('/utility/product-codes', [
            'AccountNumber' => $this->client->getAccountNumber(),
        ]);

        return ProductCodesResponse::fromArray($data);
    }

    public function pickupPointsForAddress(): PickupPointsBuilder
    {
        return new PickupPointsBuilder($this->client);
    }

    public function dropoffPointsForInternationalAddress(): DropoffPointsBuilder
    {
        return new DropoffPointsBuilder($this->client);
    }
}
