<?php

namespace Modules\Auth\Data\TransferObjects;

use Spatie\LaravelData\Data;

class VerifyOtpResponseData extends Data
{
    public function __construct(
        public UserResource $user,
        public string $token,
        public string $message,
    ) {}
}
