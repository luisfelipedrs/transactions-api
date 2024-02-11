<?php

declare(strict_types=1);

namespace App\Application\Usecase\Transaction;

use App\Domain\Aggregate\UserAccount;
use App\Domain\Entity\Transaction;

interface MakeTransactionUseCase
{
    public function execute(array $transactionRequest): Transaction;
}