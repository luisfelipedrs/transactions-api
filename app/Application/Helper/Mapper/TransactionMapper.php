<?php

declare(strict_types=1);

namespace App\Application\Helper\Mapper;

use App\Domain\Aggregate\UserAccount;
use App\Domain\Entity\Transaction;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\WalletRepository;
use App\Domain\ValueObject\Shared\ExternalIdValueObject;
use App\Domain\ValueObject\Shared\UuidGenerator;
use App\Domain\ValueObject\Transaction\TransactionAmount;
use App\Domain\ValueObject\Transaction\TransactionId;
use App\Domain\ValueObject\Transaction\TransactionStatus;
use App\Infra\Model\TransactionModel;

class TransactionMapper
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly WalletRepository $walletRepository,
        private readonly UuidGenerator $uuidGenerator
    ) {}

    public function toDomain(TransactionModel $transactionModel): Transaction
    {
        $payerUser = $this->userRepository->findByInternalId($transactionModel->payer_id);
        $payerWallet = $this->walletRepository->findByUserId($payerUser->id->value);
        $payerUserAccount = new UserAccount($payerUser, $payerWallet);

        $payeeUser = $this->userRepository->findByInternalId($transactionModel->payee_id);
        $payeeWallet = $this->walletRepository->findByUserId($payeeUser->id->value);
        $payeeUserAccount = new UserAccount($payeeUser, $payeeWallet);

        return new Transaction(
            $payerUserAccount,
            $payeeUserAccount,
            new TransactionAmount($transactionModel->amount),
            TransactionStatus::from(strtolower($transactionModel->status)),
            new ExternalIdValueObject($this->uuidGenerator->generateUuid()),
            new TransactionId($transactionModel->id)
        );
    }

    public function toModel(Transaction $transaction): TransactionModel
    {
        $transactionModel = new TransactionModel();
        $transactionModel->external_id = $transaction->externalId->value;
        $transactionModel->payer_id = $transaction->payerAccount->user->id->value;
        $transactionModel->payee_id = $transaction->payeeAccount->user->id->value;
        $transactionModel->amount = $transaction->amount->value;
        $transactionModel->status = $transaction->status->name;
        $transactionModel->id = $transaction->id ? $transaction->id->value : null;
        return $transactionModel;
    }
}
