<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Entity\Transaction;
use App\Domain\Repository\TransactionNotificationRepository;
use App\Domain\ValueObject\Transaction\TransactionNotificationStatus;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;

class TransactionNotificationRepositoryImpl implements TransactionNotificationRepository
{
    public function save(Transaction $transaction): void
    {
        Db::table('notifications')->insert([
            'transaction_id' => $transaction->id->value,
            'message' => 'Transferência recebida no valor de R$ ' . $transaction->amount->value . '.',
            'payee_email' => $transaction->payeeAccount->user->email->value,
            'status' => TransactionNotificationStatus::PENDING->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function getPending(): array
    {
        return Db::table('notifications')
            ->where('status', TransactionNotificationStatus::PENDING->name)
            ->get()
            ->all();
    }
}