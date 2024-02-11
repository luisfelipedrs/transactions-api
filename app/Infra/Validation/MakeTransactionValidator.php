<?php

declare(strict_types=1);

namespace App\Infra\Validation;

use Hyperf\Validation\Request\FormRequest;

class MakeTransactionValidator extends FormRequest
{
    public function rules(): array
    {
        return [
            'payer' => 'required|string',
            'payee' => 'required|string',
            'value' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            // payer
            'payer.required' => json_encode(['error' => 'ID do pagador é obrigatório.']),
            'payer.string' => json_encode(['error' => 'ID do pagador inválido.']),

            // payee
            'payee.required' => json_encode(['error' => 'ID do beneficiário é obrigatório.']),
            'payee.string' => json_encode(['error' => 'ID do beneficiário inválido.']),

            // value
            'value.required' => json_encode(['error' => 'Valor da transação é obrigatório.']),
            'value.string' => json_encode(['error' => 'Valor da transação inválido.']),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}