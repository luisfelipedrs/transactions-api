<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Application\Usecase\User\CreateUserAccountUseCase;
use App\Application\Usecase\User\FindUserAccountUseCase;
use App\Domain\Aggregate\UserAccount;
use Hyperf\DbConnection\Db;

class FindUserServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $userRequest = [
            'userType' => 'seller',
            'name' => 'John Doe',
            'email' => 'john.doe1@example.com',
            'password' => 'password123',
            'documentType' => 'cnpj',
            'document' => '07372640000171',
        ];

        $createUserService = \Hyperf\Support\make(CreateUserAccountUseCase::class);
        $userAccount = $createUserService->execute($userRequest);
        
        $findUserService = \Hyperf\Support\make(FindUserAccountUseCase::class);
        $userToFind = $findUserService->execute($userAccount->user->externalId->value);

        $this->assertInstanceOf(UserAccount::class, $userToFind);

        Db::table('users')->delete($userAccount->user->id->value);
    }
}