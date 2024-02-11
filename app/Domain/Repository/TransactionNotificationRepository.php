<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Transaction;

interface TransactionNotificationRepository
{
    public function save(Transaction $transaction): void;
    public function getPending(): array;
}