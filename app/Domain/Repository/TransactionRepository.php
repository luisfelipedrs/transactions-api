<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Transaction;

interface TransactionRepository
{
    public function save(Transaction $transaction): Transaction;
    public function update(Transaction $transaction): Transaction;
}