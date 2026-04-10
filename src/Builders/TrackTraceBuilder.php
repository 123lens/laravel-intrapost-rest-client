<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Builders;

use Budgetlens\Intrapost\Builders\Concerns\HasAddress;
use Budgetlens\Intrapost\Builders\Concerns\HasLabelFormat;
use Budgetlens\Intrapost\Builders\Concerns\HasOptions;
use Budgetlens\Intrapost\Builders\Concerns\HasSalesChannel;
use Budgetlens\Intrapost\DTOs\CustomsInfo;
use Budgetlens\Intrapost\DTOs\CustomsProduct;
use Budgetlens\Intrapost\DTOs\PickupAddress;
use Budgetlens\Intrapost\Enums\CarrierType;
use Budgetlens\Intrapost\Enums\TrackTraceProduct;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Responses\TrackTraceCreateResponse;

class TrackTraceBuilder
{
    use HasAddress;
    use HasLabelFormat;
    use HasOptions;
    use HasSalesChannel;

    private ?TrackTraceProduct $product = null;
    private ?float $weightKg = null;
    private ?string $reference = null;
    private ?string $orderReference = null;
    private bool $sendMailToRecipient = false;
    private ?string $bolComOrderId = null;
    private ?PickupAddress $pickupAddress = null;
    private ?CustomsInfo $customs = null;

    public function __construct(
        private readonly IntrapostClient $client,
    ) {
    }

    public function product(TrackTraceProduct $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function standardParcel(): static
    {
        return $this->product(TrackTraceProduct::StandardParcel);
    }

    public function insuredParcel(): static
    {
        return $this->product(TrackTraceProduct::InsuredParcel);
    }

    public function registeredParcel(): static
    {
        return $this->product(TrackTraceProduct::RegisteredParcel);
    }

    public function mailboxParcel(): static
    {
        return $this->product(TrackTraceProduct::MailboxParcel);
    }

    public function eveningDelivery(): static
    {
        return $this->product(TrackTraceProduct::StandardParcelWithEveningDelivery);
    }

    public function withAgeCheck(): static
    {
        return $this->product(TrackTraceProduct::StandardParcelWithAgeCheck);
    }

    public function withSignature(): static
    {
        return $this->product(TrackTraceProduct::StandardParcelSignature);
    }

    public function pickupLocation(): static
    {
        return $this->product(TrackTraceProduct::ParcelViaPickupLocation);
    }

    public function weight(float $kg): static
    {
        $this->weightKg = $kg;

        return $this;
    }

    public function reference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function orderReference(string $reference): static
    {
        $this->orderReference = $reference;

        return $this;
    }

    public function sendMailToRecipient(bool $send = true): static
    {
        $this->sendMailToRecipient = $send;

        return $this;
    }

    public function bolComOrderId(string $orderId): static
    {
        $this->bolComOrderId = $orderId;

        return $this;
    }

    public function dimensions(int $lengthCm, int $widthCm, int $heightCm): static
    {
        $this->option(3, (string) $lengthCm);
        $this->option(4, (string) $widthCm);
        $this->option(5, (string) $heightCm);

        return $this;
    }

    public function pickup(PickupAddress $address): static
    {
        $this->pickupAddress = $address;

        return $this;
    }

    public function pickupAt(CarrierType $carrier, string $carrierId, string $countryCode): static
    {
        $this->pickupAddress = new PickupAddress(
            carrierType: $carrier,
            countryCode: $countryCode,
            carrierId: $carrierId,
        );

        return $this;
    }

    public function customs(?string $invoiceNumber = null, array $products = []): static
    {
        $this->customs = new CustomsInfo(
            invoiceNumber: $invoiceNumber,
            products: array_map(
                fn (array $p) => new CustomsProduct(...$p),
                $products
            ),
        );

        return $this;
    }

    public function customsInfo(CustomsInfo $customs): static
    {
        $this->customs = $customs;

        return $this;
    }

    public function send(): TrackTraceCreateResponse
    {
        $payload = array_filter([
            'AccountNumber' => $this->client->getAccountNumber(),
            'Product' => $this->product?->value,
            'WeightKg' => $this->weightKg,
            'Reference' => $this->reference,
            'OrderReference' => $this->orderReference,
            'SendMailToRecipient' => $this->sendMailToRecipient ?: null,
            'BolComOrderId' => $this->bolComOrderId,
        ], fn ($value) => $value !== null);

        $payload = array_merge(
            $payload,
            $this->getAddressPayload(),
            $this->getLabelFormatPayload(),
            $this->getOptionsPayload(),
            $this->getSalesChannelPayload(),
        );

        if ($this->pickupAddress) {
            $payload['PickupAddress'] = $this->pickupAddress->toArray();
        }

        if ($this->customs) {
            $payload['Customs'] = $this->customs->toArray();
        }

        $data = $this->client->post('/track-trace/create_3_0', $payload);

        return TrackTraceCreateResponse::fromArray($data);
    }
}
