<?php

declare(strict_types=1);

namespace App\Application\Service\User;

use App\Application\Usecase\User\FindUserAccountUseCase;
use App\Domain\Aggregate\UserAccount;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\WalletRepository;

class FindUserService implements FindUserAccountUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly WalletRepository $walletRepository
    ) {}

    public function execute(string $id): UserAccount
    {
        $user = $this->userRepository->findById($id);
        $wallet = $this->walletRepository->findByUserId($user->id->value);
        return new UserAccount($user, $wallet);
    }
}