<?php

declare(strict_types=1);

namespace App\Domain\Repository;

interface AuthorizationRepository
{
    public function isAuthorized(): bool;
}