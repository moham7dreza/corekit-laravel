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
                __('auth.otp.not_found')
            );
        }

        $maxAttempts = config('sms.otp.attempts', 3);
        if ($otp->attempts >= $maxAttempts) {
            return ApiJsonResponse::error(
                Response::HTTP_TOO_MANY_REQUESTS,
                __('auth.otp.max_attempts')
            );
        }

        $expiryMinutes = config('sms.otp.expiry_minutes', 5);
        if (Date::now()->diffInMinutes($otp->created_at) > $expiryMinutes) {
            return ApiJsonResponse::error(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                __('auth.otp.expired')
            );
        }

        if ($otp->otp_code !== $request->otp) {
            $otp->increment('attempts');

            return ApiJsonResponse::error(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                __('auth.otp.invalid')
            );
        }

        $otp->update(['used' => 1]);

        $user = User::query()->firstWhere('mobile', $request->mobile);

        $metric = metric('auth:verify')
            ->date(Date::today());

        if (! $user) {
            $user = User::query()->create([
                'name' => $request->mobile,
                'password' => Str::random(10),
                'mobile' => $request->mobile,
                'mobile_verified_at' => Date::now(),
                'email' => $request->mobile.'@local.dev',
            ]);
            $message = __('auth.otp.registered');
        } else {
            $user->update(['mobile_verified_at' => Date::now()]);
            $message = __('auth.otp.verified');
            $metric->measurable($user);
        }

        auth()->login($user);

        $metric->hourly()->record();

        $token = $user->createToken('auth-token')->plainTextToken;

        return ApiJsonResponse::success([
            'user' => $user,
            'token' => $token,
        ], message: $message);
    }
}
