<?php

declare(strict_types=1);

namespace App\Application\Service\Wallet;

use App\Domain\Entity\Wallet;
use App\Domain\Repository\WalletRepository;
use App\Application\Usecase\Wallet\FindWalletUseCase;

class FindWalletService implements FindWalletUseCase
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
    ) {}

    public function execute(int $id): Wallet
    {
        return $this->walletRepository->findById($id);
    }
}