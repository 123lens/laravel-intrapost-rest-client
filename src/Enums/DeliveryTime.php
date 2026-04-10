<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum DeliveryTime: int
{
    case Standard = 0;
    case FixedDays = 3;
}
