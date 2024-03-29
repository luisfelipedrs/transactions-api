<?php

declare(strict_types=1);

namespace App\Domain\Aggregate;

use App\Domain\Entity\User;
use App\Domain\Entity\Wallet;

class UserAccount
{
    public function __construct(
        public readonly User $user,
        public readonly Wallet $wallet
    ) {}

    public function export(): array
    {
        return [
            'user' => $this->user->export(),
            'balance' => $this->wallet->balance->value
        ];
    }
}