<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum TrackTraceOptionType: int
{
    case InsuredAmount = 0;
    case PurchasingValue = 1;
    case CommercialValue = 2;
    case ParcelLength = 3;
    case ParcelWidth = 4;
    case ParcelHeight = 5;
    case ShipmentContent = 6;
}
