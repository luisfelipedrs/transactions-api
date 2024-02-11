<?php

declare(strict_types=1);

namespace App\Infra\Model;

use Hyperf\DbConnection\Model\Model;

class TransactionModel extends Model
{
    protected ?string $table = 'transactions';

    protected array $fillable = [
        'external_id',
        'payer_id',
        'payee_id',
        'amount',
        'status'
    ];
}