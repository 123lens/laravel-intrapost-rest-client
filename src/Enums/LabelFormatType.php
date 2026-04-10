<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum LabelFormatType: int
{
    case ZplZebra150x100 = 2;
    case Pdf150x100 = 3;
}
