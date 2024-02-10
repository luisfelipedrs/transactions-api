<?php

declare(strict_types=1);

namespace App\Application\Usecase\Wallet;

use App\Domain\Entity\Wallet;

interface WithdrawMoneyUseCase
{
    public function execute(array $withdrawRequest): Wallet;
}