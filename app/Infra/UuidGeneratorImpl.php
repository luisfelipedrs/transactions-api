<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\ValueObject\Shared\UuidGenerator;
use Ramsey\Uuid\Uuid;

class UuidGeneratorImpl implements UuidGenerator
{
    public static function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}