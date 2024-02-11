<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Transaction;

class TransactionAmount
{
    public function __construct(
        public readonly string $value
    ) {}
}