<?php

declare(strict_types=1);

namespace App\Application\Usecase\Wallet;

use App\Domain\Entity\Wallet;

interface FindWalletByUserUseCase
{
    public function execute(int $id): Wallet;
}