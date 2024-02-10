<?php

declare(strict_types=1);

namespace App\Application\Helper\Mapper;

use App\Domain\Entity\Wallet;
use App\Domain\ValueObject\Wallet\WalletBalance;
use App\Domain\ValueObject\Wallet\WalletId;
use App\Domain\ValueObject\Wallet\WalletUserId;
use App\Infra\Model\WalletModel;

class WalletMapper
{
    public function toDomain(WalletModel $walletModel): Wallet
    {
        return new Wallet(
            new WalletUserId($walletModel->user_id),
            new WalletBalance($walletModel->balance),
            new WalletId($walletModel->id)
        );
    }

    public function toModel(Wallet $wallet): WalletModel
    {
        $walletModel = new WalletModel();
        $walletModel->user_id = $wallet->walletUserId->value;
        $walletModel->balance = $wallet->balance->value;
        $walletModel->id = $wallet->id ? $wallet->id->value : null; 
        return $walletModel;
    }
}