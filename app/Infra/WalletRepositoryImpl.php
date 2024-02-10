<?php

declare(strict_types=1);

namespace App\Infra;

use App\Application\Helper\Mapper\WalletMapper;
use App\Domain\Entity\Wallet;
use App\Domain\Repository\WalletRepository;
use App\Infra\Model\WalletModel;
use InvalidArgumentException;

class WalletRepositoryImpl implements WalletRepository
{
    public function __construct(
        private readonly WalletMapper $walletMapper
    ){}

    public function save(Wallet $wallet): Wallet
    {
        $walletModel = $this->walletMapper->toModel($wallet);
        $walletModel->save();
        return $this->walletMapper->toDomain($walletModel);
    }

    public function update(Wallet $wallet): Wallet
    {
        $walletModel = WalletModel::find($wallet->id->value);
        $this->checkIfWalletExists($walletModel);
        
        $walletModel->balance = $wallet->balance->value;
        $walletModel->save();
        return $this->walletMapper->toDomain($walletModel);
    }

    public function findById(int $id): Wallet
    {
        $walletModel = WalletModel::find($id);
        $this->checkIfWalletExists($walletModel);
        return $this->walletMapper->toDomain($walletModel);
    }

    public function findByUserId(int $walletUserId): Wallet
    {
        $walletModel = WalletModel::where('user_id', $walletUserId)->first();
        $this->checkIfWalletExists($walletModel);
        return $this->walletMapper->toDomain($walletModel);
    }

    private function checkIfWalletExists(WalletModel $walletModel): void
    {
        if (!$walletModel) {
            throw new InvalidArgumentException("Carteira não encontrada.");
        }
    }
}