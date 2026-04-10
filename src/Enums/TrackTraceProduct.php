<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum TrackTraceProduct: int
{
    case StandardParcel = 1;
    case InsuredParcel = 2;
    case RegisteredParcel = 3;
    case InsuredLetter = 4;
    case RegisteredLetter = 5;
    case StandardParcelStatedAddress = 6;
    case StandardParcelStatedAddressSignature = 7;
    case MailboxParcel = 8;
    case ParcelViaPickupLocation = 9;
    case StandardParcelWithEveningDelivery = 10;
    case StandardParcelWithAgeCheck = 11;
    case StandardParcelSignature = 12;
}
