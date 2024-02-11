<?php

declare(strict_types=1);

namespace App\Application\Usecase\Transaction;

use App\Domain\Entity\Transaction;

interface SendNotificationUseCase
{
    public function execute(Transaction $transaction): void;
}