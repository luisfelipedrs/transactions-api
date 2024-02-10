<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Shared;

interface UuidGenerator
{
    public static function generateUuid(): string;
}