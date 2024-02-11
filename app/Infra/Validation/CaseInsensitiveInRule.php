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
        if (!is_string($value)) return false;
        return in_array(strtolower($value), $this->allowedValues);
    }

    public function message(): string
    {
        return json_encode(['error' => 'O campo :attribute não é um valor válido. Opções: ' . $this->buildMessage() . '.']);
    }

    private function buildMessage(): string
    {
        $capitalizedArray = array_map(function ($word) {
            return ucwords($word);
        }, $this->allowedValues);

        return join(", ", $capitalizedArray);
    }
}