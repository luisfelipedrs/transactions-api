<?php

declare(strict_types=1);

namespace App\Infra;

use App\Application\Helper\Mapper\TransactionMapper;
use App\Domain\Entity\Transaction;
use App\Domain\Repository\TransactionRepository;
use App\Exception\ResourceNotFoundException;
use App\Infra\Model\TransactionModel;
use Hyperf\DbConnection\Db;
use InvalidArgumentException;

class TransactionRepositoryImpl implements TransactionRepository
{
    public function __construct(
        private readonly TransactionMapper $transactionMapper
    ) {}

    public function save(Transaction $transaction): Transaction
    {
        return Db::transaction(function () use ($transaction) {
            $transactionModel = $this->transactionMapper->toModel($transaction);
            $transactionModel->save();
            Db::commit();
            return $this->transactionMapper->toDomain($transactionModel);
        });
    }

    public function update(Transaction $transaction): Transaction
    {
        $transactionModel = TransactionModel::find($transaction->id->value);

        if (!$transactionModel) {
            throw new ResourceNotFoundException("Transação não encontrada.");
        }
        
        $transactionModel->status = $transaction->status->name;
        $transactionModel->save();
        return $this->transactionMapper->toDomain($transactionModel);
    }
}