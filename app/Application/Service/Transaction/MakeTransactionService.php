<?php

declare(strict_types=1);

namespace App\Application\Service\Transaction;

use App\Application\Usecase\Transaction\MakeTransactionUseCase;
use App\Application\Usecase\Transaction\SendNotificationUseCase;
use App\Domain\Aggregate\UserAccount;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\User;
use App\Domain\Repository\AuthorizationRepository;
use App\Domain\Repository\TransactionRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\WalletRepository;
use App\Domain\ValueObject\Shared\ExternalIdValueObject;
use App\Domain\ValueObject\Shared\UuidGenerator;
use App\Domain\ValueObject\Transaction\TransactionAmount;
use App\Domain\ValueObject\Transaction\TransactionStatus;
use App\Domain\ValueObject\User\UserType;
use Exception;
use InvalidArgumentException;

class MakeTransactionService implements MakeTransactionUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly WalletRepository $walletRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly SendNotificationUseCase $sendNotificationUseCase,
        private readonly AuthorizationRepository $authorizationRepository,
        private readonly UuidGenerator $uuidGenerator
    ) {}

    public function execute(array $transactionRequest): Transaction
    {
        $this->validateTransaction($transactionRequest);

        $transactionPayer = $transactionRequest['payer'];
        $transactionPayee = $transactionRequest['payee'];
        $transactionAmount = $transactionRequest['value'];

        $payerUser = $this->userRepository->findById($transactionPayer);
        $payerWallet = $this->walletRepository->findById($payerUser->id->value);
        $payerUserAccount = new UserAccount($payerUser, $payerWallet);

        $payeeUser = $this->userRepository->findById($transactionPayee);
        $payeeWallet = $this->walletRepository->findById($payeeUser->id->value);
        $payeeUserAccount = new UserAccount($payeeUser, $payeeWallet);

        $transaction = new Transaction(
            $payerUserAccount,
            $payeeUserAccount,
            new TransactionAmount($transactionAmount),
            TransactionStatus::CREATED,
            new ExternalIdValueObject($this->uuidGenerator->generateUuid())
        );

        $transaction = $this->transactionRepository->save($transaction);

        try {
            $payerWallet = $payerWallet->subtractFromBalance($transactionAmount);
            $payeeWallet = $payeeWallet->addToBalance($transactionAmount);

            $this->validateTransactionAuthorization();

            $this->walletRepository->update($payerWallet);
            $this->walletRepository->update($payeeWallet);
    
            $transaction = $transaction->changeTransactionStatus($transaction, TransactionStatus::EXECUTED);
            $transaction = $this->transactionRepository->update($transaction);

            $this->sendNotificationUseCase->execute($transaction);
            return $transaction;
        } catch (Exception $e) {
            $transaction = $transaction->changeTransactionStatus($transaction, TransactionStatus::ERROR);
            $this->transactionRepository->update($transaction);
            throw $e;
        }
    }

    private function validateTransaction(array $transactionRequest): void
    {
        $transactionPayer = $transactionRequest['payer'];
        $transactionPayee = $transactionRequest['payee'];

        $this->validateSamePayerAndPayee($transactionPayer, $transactionPayee);

        $payerUser = $this->userRepository->findById($transactionPayer);
        $this->validatePayerUserType($payerUser);
    }

    private function validateSamePayerAndPayee(string $payerId, string $payeeId): void
    {
        if ($payerId === $payeeId) {
            throw new InvalidArgumentException("O ID do pagador não pode ser o mesmo do beneficiário.");
        }
    }

    private function validatePayerUserType(User $payerUser): void
    {
        if ($payerUser->userType === UserType::Seller) {
            throw new InvalidArgumentException("Usuários lojistas não podem realizar transferências.");
        }
    }

    private function validateTransactionAuthorization(): void
    {
        if (!$this->authorizationRepository->isAuthorized()) {
            throw new InvalidArgumentException("Transação não autorizada.");
        }
    }
}