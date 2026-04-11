<?php

namespace Modules\Auth\Data\TransferObjects;

use Spatie\LaravelData\Data;

class VerifyOtpRequestData extends Data
{
    public function __construct(
        public string $mobile,
        public string $token,
        public string $otpCode,
    ) {}
}
