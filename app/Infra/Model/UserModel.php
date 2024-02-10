<?php

declare(strict_types=1);

namespace App\Infra\Model;

use Hyperf\DbConnection\Model\Model;

class UserModel extends Model
{
    protected ?string $table = 'users';

    protected array $fillable = [
        'external_id',
        'user_type',
        'name',
        'email',
        'password',
        'document_type',
        'document'
    ];
}