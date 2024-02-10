<?php

declare(strict_types=1);

namespace App\Application\Service\Wallet;

use App\Domain\Entity\Wallet;
use App\Domain\Repository\WalletRepository;
use App\Application\Usecase\Wallet\WithdrawMoneyUseCase;

class WithdrawMoneyService implements WithdrawMoneyUseCase
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
    ) {}

    public function execute(array $withdrawRequest): Wallet
    {
        $wallet = $this->walletRepository->findByUserId($withdrawRequest['walletUserId']);
        $wallet = $wallet->subtractFromBalance($withdrawRequest['value']);
        return $this->walletRepository->update($wallet);
    }
}