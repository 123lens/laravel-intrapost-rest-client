<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Laravel\Facades;

use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Resources\MailPieceResource;
use Budgetlens\Intrapost\Resources\OrderResource;
use Budgetlens\Intrapost\Resources\TrackTraceResource;
use Budgetlens\Intrapost\Resources\UtilityResource;
use Illuminate\Support\Facades\Facade;

/**
 * @method static MailPieceResource mailPiece()
 * @method static OrderResource order()
 * @method static TrackTraceResource trackTrace()
 * @method static UtilityResource utility()
 *
 * @see IntrapostClient
 */
class Intrapost extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IntrapostClient::class;
    }
}
