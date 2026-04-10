<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Resources;

use Budgetlens\Intrapost\Builders\DailyMailOption1Builder;
use Budgetlens\Intrapost\Builders\DailyMailOption2Builder;
use Budgetlens\Intrapost\Builders\DailyMailOption3Builder;

class OrderResource extends AbstractResource
{
    public function createDailyMailOption1(): DailyMailOption1Builder
    {
        return new DailyMailOption1Builder($this->client);
    }

    public function createDailyMailOption2(): DailyMailOption2Builder
    {
        return new DailyMailOption2Builder($this->client);
    }

    public function createDailyMailOption3(): DailyMailOption3Builder
    {
        return new DailyMailOption3Builder($this->client);
    }
}
