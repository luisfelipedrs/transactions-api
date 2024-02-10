<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Wallet;

enum WalletOperation: string
{
    case add = 'add';
    case subtract = 'subtract';

    public static function all(): array
    {
        return [
            self::add->name,
            self::subtract->name,
        ];
    }
}