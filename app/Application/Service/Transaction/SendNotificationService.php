<?php

declare(strict_types=1);

namespace App\Application\Service\Transaction;

use App\Application\Usecase\Transaction\SendNotificationUseCase;
use App\Domain\Entity\Transaction;
use App\Domain\Repository\NotificationRepository;
use App\Domain\Repository\TransactionNotificationRepository;
use App\Domain\Repository\TransactionRepository;
use App\Domain\ValueObject\Transaction\TransactionNotificationStatus;
use GuzzleHttp\Client;
use Hyperf\DbConnection\Db;

class SendNotificationService implements SendNotificationUseCase
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly TransactionNotificationRepository $transactionNotificationRepository,
        private readonly NotificationRepository $notificationRepository,
        private readonly Client $httpClient
    ) {}

    public function execute(Transaction $transaction): void
    {
        $this->transactionNotificationRepository->save($transaction);
    }

    public function sendNotifications()
    {
        $pending = $this->transactionNotificationRepository->getPending();

        foreach ($pending as $notification) {
            if ($this->sendTransactionNotification()) {
                Db::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['status' => TransactionNotificationStatus::SENT->name]);
            }
        }
    }

    private function sendTransactionNotification(): bool
    {
        return $this->notificationRepository->didSendNotification();
    }
}