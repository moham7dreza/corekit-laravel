<?php

return [
    'otp' => [
        'sent' => 'Verification code sent successfully',
        'verified' => 'Login successful',
        'registered' => 'Registration successful',
        'not_found' => 'Verification code not found',
        'max_attempts' => 'Maximum attempts exceeded for this code',
        'expired' => 'This code has expired',
        'invalid' => 'Invalid verification code',
        'wait' => 'Please wait :seconds seconds before requesting a new code',
    ],

    'validation' => [
        'mobile_required' => 'The mobile number is required.',
        'mobile_digits' => 'The mobile number must be 11 digits.',
        'mobile_regex' => 'The mobile number format is invalid.',
        'otp_required' => 'The verification code is required.',
        'otp_digits' => 'The verification code must be 4 digits.',
        'token_required' => 'The token is required.',
    ],
];
