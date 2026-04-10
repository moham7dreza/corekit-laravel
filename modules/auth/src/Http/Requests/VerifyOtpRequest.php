<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'string', 'max:15'],
            'otp' => ['required', 'string', 'digits:4'],
            'token' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'otp.digits' => 'کد تایید باید ۴ رقم باشد',
        ];
    }
}
