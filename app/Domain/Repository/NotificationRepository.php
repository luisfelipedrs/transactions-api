<?php

declare(strict_types=1);

namespace App\Domain\Repository;

interface NotificationRepository
{
    public function didSendNotification(): bool;
}