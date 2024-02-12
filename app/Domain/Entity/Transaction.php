<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Aggregate\UserAccount;
use App\Domain\ValueObject\Shared\ExternalIdValueObject;
use App\Domain\ValueObject\Transaction\TransactionAmount;
use App\Domain\ValueObject\Transaction\TransactionId;
use App\Domain\ValueObject\Transaction\TransactionStatus;

class Transaction
{
    public function __construct(
        public readonly UserAccount $payerAccount,
        public readonly UserAccount $payeeAccount,
        public readonly TransactionAmount $amount,
        public readonly TransactionStatus $status,
        public readonly ?ExternalIdValueObject $externalId = null,
        public ?TransactionId $id = null,
    ) {}

    public function isTransactionExecuted(): bool
    {
        return $this->status === TransactionStatus::EXECUTED;
    }

    public function changeTransactionStatus(Transaction $transaction, TransactionStatus $transactionStatus): self
    {
        return new Transaction(
            $transaction->payerAccount,
            $transaction->payeeAccount,
            $transaction->amount,
            $transactionStatus,
            $transaction->externalId,
            $transaction->id
        ); 
    }

    public function export(): array
    {
        return [
            'id' => $this->externalId->value,
            'payer' => $this->payerAccount->export(),
            'payee' => $this->payeeAccount->export(),
            'amount' => $this->amount->value,
            'status' => $this->status->name,
        ];
    }
}