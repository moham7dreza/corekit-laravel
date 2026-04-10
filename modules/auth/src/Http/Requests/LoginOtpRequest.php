<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'numeric', 'digits:11', 'regex:/^09[0-9]{9}$/'],
        ];
    }

    public function messages(): array
    {
        // TODO: translate
        return [
            'mobile.required' => 'شماره موبایل الزامی است',
            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست',
        ];
    }
}
