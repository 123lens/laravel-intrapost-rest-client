<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Resources;

use Budgetlens\Intrapost\IntrapostClient;

abstract class AbstractResource
{
    public function __construct(
        protected readonly IntrapostClient $client,
    ) {
    }
}
