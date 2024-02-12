<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Wallet\WalletBalance;
use App\Domain\ValueObject\Wallet\WalletId;
use App\Domain\ValueObject\Wallet\WalletUserId;
use App\Exception\ValidationException;

class Wallet
{
    public function __construct(
        public readonly WalletUserId $walletUserId,
        public readonly WalletBalance $balance,
        public readonly ?WalletId $id = null
    ) {}

    public function addToBalance(string $amount): self
    {
        $this->validateBeforeAdd($amount);
        $newBalance = bcadd($this->balance->value, $amount, 2);
        return new Wallet($this->walletUserId, new WalletBalance($newBalance), $this->id);
    }

    public function subtractFromBalance(string $amount): self
    {
        $this->validateBeforeSubtract($amount);
        $newBalance = bcsub($this->balance->value, $amount, 2);
        return new Wallet($this->walletUserId, new WalletBalance($newBalance), $this->id);
    }

    private function validateBeforeAdd(string $amount): void
    {
        if (bccomp($amount, '0', 2) <= 0) {
            throw new ValidationException("O valor a ser adicionado deve ser maior que zero.");
        }
    }

    private function validateBeforeSubtract(string $amount): void
    {
        if (bccomp($amount, '0', 2) <= 0) {
            throw new ValidationException("O valor a ser subtraído deve ser maior que zero.");
        }

        if (!$this->balance->hasEnoughFunds($amount)) {
            throw new ValidationException("Saldo insuficiente para realizar a transação.");
        }
    }

    public function export(): array
    {
        return [
            'balance' => $this->balance->value
        ];
    }
}