<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObject\Shared\IdValueObject;

class IdValueObjectTest extends TestCase
{
    public function testValidId(): void
    {
        $id = new IdValueObject(1);
        $this->assertEquals(1, $id->value);
    }

    public function testInvalidIdZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ID não pode ser menor ou igual a zero.');

        new IdValueObject(0);
    }

    public function testInvalidIdNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ID não pode ser menor ou igual a zero.');

        new IdValueObject(-1);
    }
}
