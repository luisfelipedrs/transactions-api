<?php

declare(strict_types=1);

namespace App\Application\Usecase\Wallet;

use App\Domain\Entity\Wallet;

interface FindWalletUseCase
{
    public function execute(int $id): Wallet;
}