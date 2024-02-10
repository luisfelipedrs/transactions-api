<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Helper\Mapper\UserMapper;
use App\Application\Usecase\User\CreateUserAccountUseCase;
use App\Application\Usecase\User\FindUserAccountUseCase;
use App\Application\Usecase\Wallet\DepositMoneyUseCase;
use App\Infra\Validation\CreateUserValidator;
use App\Infra\Validation\WalletOperationValidator;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class UserController
{
    public function __construct(
        private readonly CreateUserAccountUseCase $createUserAccountUseCase,
        private readonly DepositMoneyUseCase $depositMoneyUseCase,
        private readonly FindUserAccountUseCase $FindUserAccountUseCase,
        private readonly UserMapper $userMapper
    ){}

    public function createUser(CreateUserValidator $request, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $user = $this->createUserAccountUseCase->execute($request);
        return $response->json($user->export());
    }

    public function handleDeposit(WalletOperationValidator $request, string $id, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $wallet = $this->depositMoneyUseCase->execute($id, $request);
        return $response->json($wallet->export());
    }

    public function findUserById(string $id, ResponseInterface $response): PsrResponseInterface
    {
        $user = $this->FindUserAccountUseCase->execute($id);
        return $response->json($user->export());
    }
}