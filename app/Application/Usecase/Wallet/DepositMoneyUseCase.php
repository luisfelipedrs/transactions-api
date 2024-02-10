<?php

declare(strict_types=1);

namespace App\Application\Usecase\Wallet;

use App\Domain\Entity\Wallet;

interface DepositMoneyUseCase
{
    public function execute(string $userId, array $depositRequest): Wallet;
}