<?php

declare(strict_types=1);

use App\Application\Usecase\User\CreateUserAccountUseCase;
use App\Domain\Entity\User;
use App\Domain\Entity\Wallet;
use PHPUnit\Framework\TestCase;
use Hyperf\DbConnection\Db;

class CreateUserServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $userRequest = [
            'userType' => 'seller',
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'documentType' => 'cnpj',
            'document' => '07372640000171',
        ];

        $userService = \Hyperf\Support\make(CreateUserAccountUseCase::class);

        $userAccount = $userService->execute($userRequest);

        $this->assertInstanceOf(User::class, $userAccount->user);
        $this->assertInstanceOf(Wallet::class, $userAccount->wallet);

        Db::table('users')->delete($userAccount->user->id->value);
    }
}