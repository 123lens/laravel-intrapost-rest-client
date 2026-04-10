<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum MeasurementType: int
{
    case IntrapostLength = 0;
    case IntrapostWidth = 1;
    case IntrapostHeight = 2;
    case CarrierLength = 3;
    case CarrierWidth = 4;
    case CarrierHeight = 5;
}
