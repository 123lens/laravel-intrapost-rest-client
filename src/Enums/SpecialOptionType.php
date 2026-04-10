<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum SpecialOptionType: int
{
    case WasLetter = 1;
    case DeviatedProcessing = 2;
    case DimensionSurcharge = 3;
    case IslandSurcharge = 4;
    case IsLarge = 9;
    case WasMailBoxParcel = 11;
    case SpecialSize = 12;
    case Fragile = 13;
}
