<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use Modules\Auth\Http\Controllers\RegisteredUserWithOTPController;
use Modules\Auth\Http\Controllers\VerifyUserWithOTPController;

Route::prefix('auth')
    ->middleware([
        'guest',
        ThrottleRequestsWithRedis::using('otp-request'),
    ])
    ->group(function (): void {
        Route::post('send-otp', RegisteredUserWithOTPController::class)
            ->name('api.auth.send-otp');

        Route::post('verify-otp', VerifyUserWithOTPController::class)
            ->name('api.auth.verify-otp');
    });
