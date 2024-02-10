<?php

declare(strict_types=1);

namespace App\Infra\Model;


class WalletModel extends Model
{
    protected ?string $table = 'wallets';

    protected array $fillable = [
        'user_id',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
