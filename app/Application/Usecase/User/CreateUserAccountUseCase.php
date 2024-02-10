<?php

declare(strict_types=1);

namespace App\Application\Usecase\User;

use App\Domain\Aggregate\UserAccount;

interface CreateUserAccountUseCase
{
    public function execute(array $user): UserAccount;
}