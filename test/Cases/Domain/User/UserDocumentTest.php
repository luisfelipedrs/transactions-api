<?php

declare(strict_types=1);

use App\Domain\ValueObject\User\DocumentType;
use App\Domain\ValueObject\User\UserDocument;
use App\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class UserDocumentTest extends TestCase
{
    public function testValidCpf(): void
    {
        $cpf = '12345678909';
        $userDocument = new UserDocument($cpf, DocumentType::CPF->name);

        $this->assertSame($cpf, $userDocument->value);
        $this->assertSame(DocumentType::CPF->name, $userDocument->documentType);
    }

    public function testInvalidCpfLength(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('CPF inválido.');

        $cpf = '12345678904545454';
        new UserDocument($cpf, strtolower(DocumentType::CPF->name));
    }

    public function testValidCnpj(): void
    {
        $cnpj = '12345678901234';
        $userDocument = new UserDocument($cnpj, DocumentType::CNPJ->name);

        $this->assertSame($cnpj, $userDocument->value);
        $this->assertSame(DocumentType::CNPJ->name, $userDocument->documentType);
    }

    public function testInvalidCnpjLength(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('CNPJ inválido.');

        $cnpj = '123456789012';
        new UserDocument($cnpj, strtolower(DocumentType::CNPJ->name));
    }
}
