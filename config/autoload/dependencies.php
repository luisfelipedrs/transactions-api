<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

return [
    // repositories
    // user
    \App\Domain\Repository\UserRepository::class => \App\Infra\UserRepositoryImpl::class,

    // wallet
    \App\Domain\Repository\WalletRepository::class => \App\Infra\WalletRepositoryImpl::class,

    // transaction
    \App\Domain\Repository\TransactionRepository::class => \App\Infra\TransactionRepositoryImpl::class,
    \App\Domain\Repository\TransactionNotificationRepository::class => \App\Infra\TransactionNotificationRepositoryImpl::class,

    // etc
    \App\Domain\Repository\AuthorizationRepository::class => \App\Infra\AuthorizationRepositoryImpl::class,
    \App\Domain\Repository\NotificationRepository::class => \App\Infra\NotificationRepositoryImpl::class,

    // usecases
    //user
    \App\Application\Usecase\User\CreateUserAccountUseCase::class => App\Application\Service\User\CreateUserService::class,
    \App\Application\Usecase\User\FindUserAccountUseCase::class => App\Application\Service\User\FindUserService::class,

    //wallet
    \App\Application\Usecase\Wallet\DepositMoneyUseCase::class => App\Application\Service\Wallet\DepositMoneyService::class,
    \App\Application\Usecase\Wallet\WithdrawMoneyUseCase::class => App\Application\Service\Wallet\WithdrawMoneyService::class,
    \App\Application\Usecase\Wallet\FindWalletUseCase::class => App\Application\Service\Wallet\FindWalletService::class,
    \App\Application\Usecase\Wallet\FindWalletByUserUseCase::class => App\Application\Service\Wallet\FindWalletByUserService::class,

    // transaction
    \App\Application\Usecase\Transaction\MakeTransactionUseCase::class => App\Application\Service\Transaction\MakeTransactionService::class,
    \App\Application\Usecase\Transaction\SendNotificationUseCase::class => App\Application\Service\Transaction\SendNotificationService::class,

    // etc
    \App\Domain\ValueObject\Shared\UuidGenerator::class => \App\Infra\UuidGeneratorImpl::class,
];
