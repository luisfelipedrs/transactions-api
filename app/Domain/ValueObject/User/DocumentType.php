<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

enum DocumentType: string
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';

    public static function all(): array
    {
        return [
            self::CPF->name,
            self::CNPJ->name,
        ];
    }
}