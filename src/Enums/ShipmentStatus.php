<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum ShipmentStatus: int
{
    case Created = 0;
    case Received = 1;
    case Sorted = 2;
    case Transported = 3;
    case Delivered = 4;
    case Returning = 5;
    case Returned = 6;
    case Rerouted = 7;
    case Pickup = 8;
    case DeliveryDateTimeChanged = 9;
    case PickupCollected = 10;
    case Processed = 11;
    case DeliveryFailed = 13;
}
