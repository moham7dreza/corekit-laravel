<?php

namespace Modules\Auth\Exceptions;

use Exception;

class OtpException extends Exception
{
    public static function notFound(): self
    {
        return new self(__('auth.otp.not_found'), 422);
    }

    public static function attemptsExceeded(): self
    {
        return new self(__('auth.otp.max_attempts'), 429);
    }

    public static function expired(): self
    {
        return new self(__('auth.otp.expired'), 422);
    }

    public static function invalid(): self
    {
        return new self(__('auth.otp.invalid'), 422);
    }
}
