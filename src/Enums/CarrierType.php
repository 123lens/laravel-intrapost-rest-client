<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum CarrierType: int
{
    case PostNL = 1;
    case GLS = 2;
    case DHL = 4;
}
