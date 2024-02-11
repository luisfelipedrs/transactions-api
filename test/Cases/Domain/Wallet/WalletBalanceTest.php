<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObject\Wallet\WalletBalance;

class WalletBalanceTest extends TestCase
{
    public function testValidBalance(): void
    {
        $balance = new WalletBalance('100.50');
        $this->assertEquals('100.50', $balance->value);
    }

    public function testHasEnoughFunds(): void
    {
        $balance = new WalletBalance('100.50');

        $this->assertTrue($balance->hasEnoughFunds('50.25'));
        $this->assertTrue($balance->hasEnoughFunds('100.50'));
        $this->assertFalse($balance->hasEnoughFunds('150.75'));
    }
}
