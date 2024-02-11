<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Repository\NotificationRepository;
use GuzzleHttp\Client;

class NotificationRepositoryImpl implements NotificationRepository
{
    public function __construct(
        private readonly Client $httpClient
    ) {}

    public function didSendNotification(): bool
    {
        $response = $this->httpClient
            ->get(\Hyperf\Support\env('NOTIFICATION_SERVICE'))
            ->getBody()
            ->getContents();

        return json_decode($response)->message;
    }
}