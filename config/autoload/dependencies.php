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
    \App\Domain\Repository\UserRepository::class => \App\Infra\UserRepositoryImpl::class,
    \App\Domain\Repository\WalletRepository::class => \App\Infra\WalletRepositoryImpl::class,

    // usecases
    //user
    App\Application\Usecase\User\CreateUserAccountUseCase::class => App\Application\Service\User\CreateUserService::class,
    \App\Application\Usecase\User\FindUserAccountUseCase::class => App\Application\Service\User\FindUserService::class,

    //wallet
    \App\Application\Usecase\Wallet\DepositMoneyUseCase::class => App\Application\Service\Wallet\DepositMoneyService::class,
    \App\Application\Usecase\Wallet\WithdrawMoneyUseCase::class => App\Application\Service\Wallet\WithdrawMoneyService::class,
    \App\Application\Usecase\Wallet\FindWalletUseCase::class => App\Application\Service\Wallet\FindWalletService::class,
    \App\Application\Usecase\Wallet\FindWalletByUserUseCase::class => App\Application\Service\Wallet\FindWalletByUserService::class,

    // etc
    \App\Domain\ValueObject\Shared\UuidGenerator::class => \App\Infra\UuidGeneratorImpl::class,
];
