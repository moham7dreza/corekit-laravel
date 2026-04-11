<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Modules\Auth\Data\TransferObjects\UserResource;
use Modules\Auth\Data\TransferObjects\VerifyOtpRequestData;
use Modules\Auth\Data\TransferObjects\VerifyOtpResponseData;
use Modules\Auth\Exceptions\OtpException;
use Modules\Auth\Models\Otp;

final readonly class VerifyOtpService
{
    /**
     * @throws OtpException
     */
    public function handle(VerifyOtpRequestData $data): VerifyOtpResponseData
    {
        $otp = Otp::query()->firstWhere([
            'token' => $data->token,
            'login_id' => $data->mobile,
            'used' => false,
        ]);

        if (! $otp) {
            throw OtpException::notFound();
        }

        $maxAttempts = config('sms.otp.attempts', 3);
        if ($otp->attempts >= $maxAttempts) {
            throw OtpException::attemptsExceeded();
        }

        $expiryMinutes = config('sms.otp.expiry_minutes', 5);
        if (Carbon::now()->diffInMinutes($otp->created_at) > $expiryMinutes) {
            throw OtpException::expired();
        }

        if ($otp->otp_code !== $data->otp) {
            $otp->increment('attempts');
            throw OtpException::invalid();
        }

        $otp->update(['used' => true]);

        $user = $this->findOrCreateUser($data->mobile);

        auth()->login($user);

        $accessToken = $user->createToken('auth-token')->plainTextToken;

        $message = $user->wasRecentlyCreated
            ? __('auth.otp.registered')
            : __('auth.otp.verified');

        return new VerifyOtpResponseData(
            user: UserResource::fromModel($user),
            token: $accessToken,
            message: $message
        );
    }

    private function findOrCreateUser(string $mobile): User
    {
        $user = User::query()->firstWhere('mobile', $mobile);

        $metric = metric('auth:verify')
            ->date(Date::today());

        if (! $user) {
            $user = User::query()->create([
                'name' => $mobile,
                'password' => Str::random(10),
                'mobile' => $mobile,
                'mobile_verified_at' => Date::now(),
                'email' => $mobile.'@local.dev',
            ]);
        } else {
            $user->touch('mobile_verified_at');
            $metric->measurable($user);
        }

        $metric->hourly()->record();

        return $user;
    }
}
