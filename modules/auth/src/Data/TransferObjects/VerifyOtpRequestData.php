<?php

namespace Modules\Auth\Data\TransferObjects;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class VerifyOtpRequestData extends Data
{
    public function __construct(
        public string $mobile,
        public string $token,
        public string $otp,
    ) {}
}
