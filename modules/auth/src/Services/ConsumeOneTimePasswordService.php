<?php

declare(strict_types=1);

namespace Modules\Auth\Services;

// use Amiriun\SMS\DataContracts\SendSMSDTO;
// use Amiriun\SMS\Services\SMSService;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Modules\Auth\Enums\NoticeType;
use Modules\Auth\Models\Otp;
use Random\RandomException;

final readonly class ConsumeOneTimePasswordService
{
    public function __construct(
        // private SMSService $smsService,
    ) {}

    /**
     * @throws RandomException
     */
    public function prepareAndSend(string $mobile): array
    {
        $lastOtp = Otp::query()
            ->where('login_id', $mobile)
            ->where('used', 0)
            ->orderByDesc('created_at')
            ->first();

        if ($lastOtp) {
            $secondsSinceLastOtp = Date::now()->diffInSeconds($lastOtp->created_at);
            $resendSeconds = config('sms.otp.resend_seconds', 60);

            if ($secondsSinceLastOtp < $resendSeconds) {
                $remainingTime = $resendSeconds - $secondsSinceLastOtp;
                // TODO: translate
                throw new \RuntimeException("لطفاً {$remainingTime} ثانیه صبر کنید");
            }
        }

        $otpCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $token = Str::random(60);

        $user = User::query()->firstWhere('mobile', $mobile);

        Otp::query()->updateOrCreate(
            ['login_id' => $mobile, 'used' => 0],
            [
                'token' => $token,
                'otp_code' => $otpCode,
                'login_id' => $mobile,
                'type' => NoticeType::Sms,
                'attempts' => 0,
                'user_id' => $user?->id,
            ]
        );

        //        $data = new SendSMSDTO();
        //        $data->setSenderNumber('300024444');
        //        $data->setMessage($otpCode);
        //        $data->setTo($mobile);
        //
        //        $this->smsService->send($data);

        return [
            'token' => $token,
            'otp' => $otpCode,
        ];
    }
}
