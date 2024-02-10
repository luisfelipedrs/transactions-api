<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Wallet;

class WalletBalance
{
    public function __construct(
        public readonly string $value
    ) {}

    public function hasEnoughFunds(string $amount): bool
    {
        $comparisonResult = bccomp($this->value, $amount, 2);
        return $comparisonResult >= 0;
    }
}