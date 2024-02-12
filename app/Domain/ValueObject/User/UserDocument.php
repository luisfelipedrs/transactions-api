<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\ValueObject\User\DocumentType;
use App\Exception\ValidationException;

class UserDocument
{
    public function __construct(
        public readonly string $value,
        public readonly string $documentType
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->documentType === strtolower(DocumentType::CPF->name)) {
            $this->validateCpf();
        }

        if ($this->documentType === strtolower(DocumentType::CNPJ->name)) {
            $this->validateCnpj();
        }
    }

    private function validateCpf(): void
    {
        if (strlen($this->value) !== 11) {
            throw new ValidationException('CPF inválido.');
        }
    }

    private function validateCnpj(): void
    {
        if (strlen($this->value) !== 14) {
            throw new ValidationException('CNPJ inválido.');
        }
    }
}