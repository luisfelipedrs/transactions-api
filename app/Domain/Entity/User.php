<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Shared\ExternalIdValueObject;
use App\Domain\ValueObject\User\DocumentType;
use App\Domain\ValueObject\User\UserDocument;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\User\UserPassword;
use App\Domain\ValueObject\User\UserType;

class User
{
    public function __construct(
        public readonly UserType $userType,
        public readonly UserName $name,
        public readonly UserEmail $email,
        public readonly UserPassword $password,
        public readonly DocumentType $documentType,
        public readonly UserDocument $document,
        public ?ExternalIdValueObject $externalId = null,
        public ?UserId $id = null,
    ) {}

    public function export(): array
    {
        return [
            'id' => $this->externalId->value,
            'name' => $this->name->value,
            'email' => $this->email->value,
            'userType' => $this->userType->name,
            'userDocument' => $this->document->value,
            'userDocumentType' => $this->documentType->value,
        ];
    }
}