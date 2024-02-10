<?php

declare(strict_types=1);

namespace App\Application\Service\Wallet;

use App\Application\Usecase\Wallet\DepositMoneyUseCase;
use App\Domain\Entity\Wallet;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\WalletRepository;

class DepositMoneyService implements DepositMoneyUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly WalletRepository $walletRepository
    ) {}

    public function execute(string $userId, array $depositRequest): Wallet
    {
        $user = $this->userRepository->findById($userId);
        $wallet = $this->walletRepository->findByUserId($user->id->value);
        $wallet = $wallet->addToBalance($depositRequest['value']);
        return $this->walletRepository->update($wallet);
    }
}