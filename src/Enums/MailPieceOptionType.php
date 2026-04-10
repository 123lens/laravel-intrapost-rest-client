<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum MailPieceOptionType: int
{
    case InsuredAmount = 0;
    case PurchasingValue = 1;
    case CommercialValue = 2;
    case MailpieceLength = 3;
    case MailpieceWidth = 4;
    case MailpieceHeight = 5;
    case ShipmentContent = 6;
    case ProjectCode = 7;
}
