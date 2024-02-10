<?php

declare(strict_types=1);

namespace App\Infra\Validation;

use App\Domain\ValueObject\Wallet\WalletOperation;
use Hyperf\Validation\Request\FormRequest;

class WalletOperationValidator extends FormRequest
{
    public function rules(): array
    {
        return [
            'value' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            // value
            'value.required' => json_encode(['error' => 'Valor do depósito é obrigatório.']),
            'value.string' => json_encode(['error' => 'Valor de depósito inválido.']),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}