<?php

declare(strict_types=1);

namespace App\Infra\Validation;

use Hyperf\Validation\Contract\Rule;

class CaseInsensitiveInRule implements Rule
{
    protected $allowedValues;

    public function __construct(array $allowedValues)
    {
        $this->allowedValues = array_map('strtolower', $allowedValues);
    }

    public function passes($attribute, $value): bool
    {
        return in_array(strtolower($value), $this->allowedValues);
    }

    public function message(): string
    {
        return json_encode(['message' => 'O campo :attribute não é um valor válido.']);
    }
}