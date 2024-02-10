<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Shared;

class ExternalIdValueObject
{
    public function __construct(
        public readonly string $value,
    ) {}
}