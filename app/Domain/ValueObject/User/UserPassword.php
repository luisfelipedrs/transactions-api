<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

class UserPassword
{
    public function __construct(
        public readonly string $value
    ) {}
}