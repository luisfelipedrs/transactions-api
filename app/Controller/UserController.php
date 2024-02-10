<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\HttpStatus;
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
        private readonly FindUserAccountUseCase $findUserAccountUseCase,
        private readonly UserMapper $userMapper
    ){}

    public function createUser(CreateUserValidator $request, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $userAccount = $this->createUserAccountUseCase->execute($request);
        return $response->json($userAccount->export())->withStatus(HttpStatus::CREATED);
    }

    public function handleDeposit(WalletOperationValidator $request, string $id, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $wallet = $this->depositMoneyUseCase->execute($id, $request);
        return $response->json($wallet->export())->withStatus(HttpStatus::OK);
    }

    public function findUserById(string $id, ResponseInterface $response): PsrResponseInterface
    {
        $userAccount = $this->findUserAccountUseCase->execute($id);
        return $response->json($userAccount->export())->withStatus(HttpStatus::OK);
    }
}