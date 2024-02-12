<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Wallet;

interface WalletRepository
{
    public function save(Wallet $wallet): Wallet;
    public function update(Wallet $wallet): Wallet;
    public function findById(int $id): Wallet;
    public function findByUserId(int $id): Wallet;
    public function makeTransaction(Wallet $payerWallet, Wallet $payeeWallet): void;
}