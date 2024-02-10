<?php

declare(strict_types=1);

namespace App\Application\Service\User;

use App\Application\Usecase\User\CreateUserAccountUseCase;
use App\Domain\Aggregate\UserAccount;
use App\Domain\Entity\User;
use App\Domain\Entity\Wallet;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\WalletRepository;
use App\Domain\ValueObject\Shared\ExternalIdValueObject;
use App\Domain\ValueObject\Shared\UuidGenerator;
use App\Domain\ValueObject\User\DocumentType;
use App\Domain\ValueObject\User\UserDocument;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\User\UserPassword;
use App\Domain\ValueObject\User\UserType;
use App\Domain\ValueObject\Wallet\WalletBalance;
use App\Domain\ValueObject\Wallet\WalletUserId;

class CreateUserService implements CreateUserAccountUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly WalletRepository $walletRepository,
        private readonly UuidGenerator $uuidGenerator
    ) {}

    public function execute(array $userRequest): UserAccount
    {
        $user = $this->userRepository->save(
            new User(
                UserType::from(strtolower($userRequest['userType'])),
                new UserName($userRequest['name']),
                new UserEmail($userRequest['email']),
                new UserPassword($userRequest['password']),
                DocumentType::from(strtolower($userRequest['documentType'])),
                new UserDocument($userRequest['document'], strtolower($userRequest['documentType'])),
                new ExternalIdValueObject($this->uuidGenerator->generateUuid())
            )
        );

        $wallet = $this->createUserWallet($user);
        return new UserAccount($user, $wallet);
    }

    private function createUserWallet(User $user): Wallet
    {
        $wallet = new Wallet(
            new WalletUserId($user->id->value),
            new WalletBalance('0.00')
        );

        return $this->walletRepository->save($wallet);
    }
}