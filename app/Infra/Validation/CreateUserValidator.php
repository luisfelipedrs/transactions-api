<?php

declare(strict_types=1);

namespace App\Infra\Validation;

use App\Domain\ValueObject\User\DocumentType;
use App\Domain\ValueObject\User\UserType;
use Hyperf\Validation\Request\FormRequest;
use App\Infra\Validation\CaseInsensitiveInRule;

class CreateUserValidator extends FormRequest
{
    public function rules(): array
    {
        return [
            'userType' => ['required', 'string', new CaseInsensitiveInRule(UserType::all())],
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'documentType' => ['required', 'string', new CaseInsensitiveInRule(DocumentType::all())],
            'document' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            // userType
            'userType.required' => json_encode(['error' => 'Tipo de usuário é obrigatório.']),
            'userType.string' => json_encode(['error' => 'Tipo de usuário inválido.']),

            // name
            'name.required' => json_encode(['error' => 'O nome é obrigatório.']),
            'name.string' => json_encode(['error' => 'Nome não é válido.']),

            // email
            'email.required' => json_encode(['error' => 'O e-mail é obrigatório.']),
            'email.email' => json_encode(['error' => 'O e-mail deve ser um endereço de e-mail válido.']),

            //password
            'password.required' => json_encode(['error' => 'A senha é obrigatória.']),
            'password.string' => json_encode(['error' => 'Senha não é válida.']),

            // documentType
            'documentType.required' => json_encode(['error' => 'O tipo de documento é obrigatório.']),
            'documentType.string' => json_encode(['error' => 'Tipo de documento inválido.']),

            // document
            'document.required' => json_encode(['error' => 'O documento é obrigatório.']),
            'document.string' => json_encode(['error' => 'Documento inválido.'])
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}