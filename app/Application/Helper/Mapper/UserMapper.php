<?php

declare(strict_types=1);

namespace App\Application\Helper\Mapper;

use App\Domain\Aggregate\UserAccount;
use App\Domain\Entity\User;
use App\Domain\Entity\Wallet;
use App\Domain\ValueObject\Shared\ExternalIdValueObject;
use App\Domain\ValueObject\User\DocumentType;
use App\Domain\ValueObject\User\UserDocument;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\User\UserPassword;
use App\Domain\ValueObject\User\UserType;
use App\Infra\Model\UserModel;

class UserMapper
{
    public function toDomain(UserModel $userModel): User
    {
        return new User(
            UserType::from(strtolower($userModel->user_type)),
            new UserName($userModel->name),
            new UserEmail($userModel->email),
            new UserPassword($userModel->password),
            DocumentType::from(strtolower($userModel->document_type)),
            new UserDocument($userModel->document, $userModel->document_type),
            new ExternalIdValueObject($userModel->external_id),
            new UserId($userModel->id)
        );
    }

    public function toModel(User $user): UserModel
    {
        $userModel = new UserModel();
        $userModel->external_id = $user->externalId->value;
        $userModel->user_type = $user->userType->name;
        $userModel->name = $user->name->value;
        $userModel->email = $user->email->value;
        $userModel->password = password_hash($user->password->value, PASSWORD_DEFAULT);
        $userModel->document_type = $user->documentType->name;
        $userModel->document = $user->document->value;
        $userModel->id = $user->id ? $user->id->value : null;
        return $userModel;
    }

    public function toAggregate(User $user, Wallet $wallet): UserAccount
    {
        return new UserAccount($user, $wallet); 
    }
}
