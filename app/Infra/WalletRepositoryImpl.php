<?php

declare(strict_types=1);

namespace App\Infra;

use App\Application\Helper\Mapper\WalletMapper;
use App\Domain\Entity\Wallet;
use App\Domain\Repository\WalletRepository;
use App\Exception\ResourceNotFoundException;
use App\Infra\Model\WalletModel;
use Hyperf\DbConnection\Db;
use Throwable;

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
        
        if (!$walletModel) {
            $this->throwWalletNotFoundException();
        };
        
        $walletModel->balance = $wallet->balance->value;
        $walletModel->save();
        return $this->walletMapper->toDomain($walletModel);
    }

    public function makeTransaction(Wallet $payerWallet, Wallet $payeeWallet): void
    {
        try {
            Db::transaction(function () use ($payerWallet, $payeeWallet) {
                $payerWallet = $this->update($payerWallet);
                $payeeWallet = $this->update($payeeWallet);
                Db::commit();
            });
        } catch (Throwable $e) {
            Db::rollBack();
            throw $e;
        }
    }

    public function findById(int $id): Wallet
    {
        $walletModel = WalletModel::find($id);

        if (!$walletModel) {
            $this->throwWalletNotFoundException();
        };

        return $this->walletMapper->toDomain($walletModel);
    }

    public function findByUserId(int $walletUserId): Wallet
    {
        $walletModel = WalletModel::where('user_id', $walletUserId)->first();

        if (!$walletModel) {
            $this->throwWalletNotFoundException();
        }

        return $this->walletMapper->toDomain($walletModel);
    }

    private function throwWalletNotFoundException(): void
    {
        throw new ResourceNotFoundException("Carteira não encontrada.");
    }
}