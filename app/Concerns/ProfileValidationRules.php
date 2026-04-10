<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            // 'email' => $this->emailRules($userId),
            'mobile' => $this->mobileRules($userId),
        ];
    }

    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }

    protected function mobileRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'max:11',
            'regex:/^09[0-9]{9}$/',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }
}
