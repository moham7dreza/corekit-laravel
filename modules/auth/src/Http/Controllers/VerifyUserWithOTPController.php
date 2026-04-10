<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiJsonResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Modules\Auth\Http\Requests\VerifyOtpRequest;
use Modules\Auth\Models\Otp;

final class VerifyUserWithOTPController extends Controller
{
    // TODO : move logic to service
    // translate
    public function __invoke(VerifyOtpRequest $request): JsonResponse
    {
        $otp = Otp::query()->firstWhere([
            'token' => $request->token,
            'login_id' => $request->mobile,
            'used' => 0,
        ]);

        if (! $otp) {
            return ApiJsonResponse::error(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'کد تایید یافت نشد'
            );
        }

        $maxAttempts = config('sms.otp.attempts', 3);
        if ($otp->attempts >= $maxAttempts) {
            return ApiJsonResponse::error(
                Response::HTTP_TOO_MANY_REQUESTS,
                'تعداد دفعات مجاز این کد به پایان رسید'
            );
        }

        $expiryMinutes = config('sms.otp.expiry_minutes', 5);
        if (Carbon::now()->diffInMinutes($otp->created_at) > $expiryMinutes) {
            return ApiJsonResponse::error(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'زمان مجاز این کد به پایان رسید'
            );
        }

        if ($otp->otp_code !== $request->otp) {
            $otp->increment('attempts');

            return ApiJsonResponse::error(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'کد وارد شده صحیح نمی‌باشد'
            );
        }

        $otp->update(['used' => 1]);

        $user = User::query()->firstWhere('mobile', $request->mobile);

        if (! $user) {
            $user = User::query()->create([
                'password' => Str::random(10),
                'mobile' => $request->mobile,
                'mobile_verified_at' => Date::now(),
            ]);
            $message = 'ثبت نام با موفقیت انجام شد';
        } else {
            $user->update(['mobile_verified_at' => Date::now()]);
            $message = 'با موفقیت وارد شدید';
        }

        auth()->login($user);

        $token = $user->createToken('auth-token')->plainTextToken;

        return ApiJsonResponse::success([
            'user' => $user,
            'token' => $token,
        ], message: $message);
    }
}
