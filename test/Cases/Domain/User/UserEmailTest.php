<?php

declare(strict_types=1);

use App\Domain\ValueObject\User\UserEmail;
use PHPUnit\Framework\TestCase;

class UserEmailTest extends TestCase
{
    public function testValidEmail(): void
    {
        $email = new UserEmail('user@example.com');
        $this->assertEquals('user@example.com', $email->value);
    }

    public function testInvalidEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('E-mail inválido.');

        new UserEmail('invalid_email');
    }

    public function testEmptyEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('E-mail inválido.');

        new UserEmail('');
    }
}
