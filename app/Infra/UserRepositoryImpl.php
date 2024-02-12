<?php

declare(strict_types=1);

namespace App\Infra;

use App\Application\Helper\Mapper\UserMapper;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\Exception\ResourceNotFoundException;
use App\Exception\ValidationException;
use App\Infra\Model\UserModel;

class UserRepositoryImpl implements UserRepository
{
    public function __construct(
        private readonly UserMapper $userMapper
    ){}

    public function save(User $user): User
    {
        $this->validateUser($user);
        $userModel = $this->userMapper->toModel($user);
        $userModel->save();
        return $this->userMapper->toDomain($userModel);
    }

    public function findById(string $externalId): User
    {
        $userModel = UserModel::where('external_id', $externalId)->first();

        if (!$userModel) {
            $this->throwUserNotFoundException();
        }

        return $this->userMapper->toDomain($userModel);
    }

    public function findByInternalId(int $id): User
    {
        $userModel = UserModel::where('id', $id)->first();

        if (!$userModel) {
            $this->throwUserNotFoundException();
        }

        return $this->userMapper->toDomain($userModel);
    }

    private function validateUser(User $user): void
    {
        if (UserModel::where('email', $user->email->value)->first()) {
            throw new ValidationException('E-mail já cadastrado.');
        }
        
        if (UserModel::where('document', $user->document->value)->first()) {
            throw new ValidationException('Documento já cadastrado.');
        }
    }

    private function throwUserNotFoundException(): void
    {
        throw new ResourceNotFoundException("Usuário não encontrado.");
    }
}