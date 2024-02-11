<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Transaction;

enum TransactionNotificationStatus
{
    case PENDING;
    case SENT;
}