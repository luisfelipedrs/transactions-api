<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Shared;

use App\Exception\ValidationException;

class IdValueObject
{
    public function __construct(
        public readonly int $value
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->value <= 0) {
            throw new ValidationException('ID não pode ser menor ou igual a zero.');
        }
    }
}