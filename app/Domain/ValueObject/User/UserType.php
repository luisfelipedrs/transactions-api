<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

enum UserType: string
{
    case Common = 'common';
    case Seller = 'seller';

    public static function all(): array
    {
        return [
            self::Common->name,
            self::Seller->name,
        ];
    }
}