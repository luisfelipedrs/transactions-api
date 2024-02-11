<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Transaction;

enum TransactionStatus: string
{
    case CREATED = 'created';
    case EXECUTED = 'executed';
    case ERROR = 'error';
}