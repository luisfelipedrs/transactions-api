<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Repository\AuthorizationRepository;
use GuzzleHttp\Client;

class AuthorizationRepositoryImpl implements AuthorizationRepository
{
    public function __construct(
        private readonly Client $httpClient
    ) {}

    public function isAuthorized(): bool
    {
        $response = $this->httpClient
            ->get(\Hyperf\Support\env('AUTHORIZATION_SERVICE'))
            ->getBody()
            ->getContents();

        return json_decode($response)->message === 'Autorizado' ? true : false;
    }
}