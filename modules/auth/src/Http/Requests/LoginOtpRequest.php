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
        return [
            'mobile.required' => __('auth.validation.mobile_required'),
            'mobile.digits' => __('auth.validation.mobile_digits'),
            'mobile.regex' => __('auth.validation.mobile_regex'),
        ];
    }
}
