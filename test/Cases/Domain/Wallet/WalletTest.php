<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Wallet;
use App\Domain\ValueObject\Wallet\WalletBalance;
use App\Domain\ValueObject\Wallet\WalletId;
use App\Domain\ValueObject\Wallet\WalletUserId;

class WalletTest extends TestCase
{
    public function testAddToBalance(): void
    {
        $wallet = new Wallet(new WalletUserId(1), new WalletBalance('100.50'), new WalletId(123));

        $newWallet = $wallet->addToBalance('50.25');

        $this->assertInstanceOf(Wallet::class, $newWallet);
        $this->assertEquals('150.75', $newWallet->balance->value);
    }

    public function testSubtractFromBalance(): void
    {
        $wallet = new Wallet(new WalletUserId(1), new WalletBalance('100.50'), new WalletId(123));

        $newWallet = $wallet->subtractFromBalance('25.25');

        $this->assertInstanceOf(Wallet::class, $newWallet);
        $this->assertEquals('75.25', $newWallet->balance->value);
    }

    public function testInvalidAddAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("O valor a ser adicionado deve ser maior que zero.");

        $wallet = new Wallet(new WalletUserId(1), new WalletBalance('100.50'));
        $wallet->addToBalance('0');
    }

    public function testInvalidSubtractAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("O valor a ser subtraído deve ser maior que zero.");

        $wallet = new Wallet(new WalletUserId(1), new WalletBalance('100.50'));
        $wallet->subtractFromBalance('0');
    }

    public function testInsufficientFunds(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Saldo insuficiente para realizar a transação.");

        $wallet = new Wallet(new WalletUserId(1), new WalletBalance('100.50'));
        $wallet->subtractFromBalance('150.75');
    }

    public function testExport(): void
    {
        $wallet = new Wallet(new WalletUserId(1), new WalletBalance('100.50'), new WalletId(123));

        $exportedData = $wallet->export();

        $this->assertEquals(['balance' => '100.50'], $exportedData);
    }
}