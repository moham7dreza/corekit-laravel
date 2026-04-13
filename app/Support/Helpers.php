<?php

use App\Enums\Environment;
use App\Enums\UserId;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Modules\Monitoring\Jobs\MongoLogJob;

if (! function_exists('module_path')) {
    function module_path(string $module, string $path = ''): string
    {
        $basePath = base_path('modules/'.lcfirst($module).'/src');

        return $path ? $basePath.'/'.$path : $basePath;
    }
}

if (! function_exists('ondemand_info')) {
    function ondemand_info(string $message, array $context = [], string $file = 'custom'): void
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/'.$file.'.log'),
            'level' => 'info',
        ])->info($message, $context);
    }
}

if (! function_exists('mongo_info')) {
    function mongo_info(
        string $log_key,
        array $data,
        bool $queueable = false
    ): void {
        if (app()->runningUnitTests()) {
            return;
        }

        if ($queueable) {
            dispatch(new MongoLogJob($data, $log_key));
        } else {
            new MongoLogJob($data, $log_key)->handle();
        }
    }
}

if (! function_exists('isEnvStaging')) {
    function isEnvStaging(): bool
    {
        return app()->environment(Environment::Staging->value);
    }
}

if (! function_exists('isEnvLocalOrTesting')) {
    function isEnvLocalOrTesting(): bool
    {
        if (app()->isLocal()) {
            return true;
        }

        return app()->runningUnitTests();
    }
}

if (! function_exists('getSqlWithBindings')) {
    function getSqlWithBindings(EloquentBuilder|QueryBuilder $query): string
    {
        return Str::replaceArray('?', $query->getBindings(), $query->toSql());
    }
}

if (! function_exists('userIdIs')) {
    function userIdIs(UserId ...$Ids): bool
    {
        $currentUserId = request()->user()?->id;
        $resolvedCase = is_int($currentUserId) ? UserId::tryFrom($currentUserId) : null;

        return $resolvedCase && in_array($resolvedCase, $Ids);
    }
}

if (! function_exists('admin')) {
    function admin(): User
    {
        return User::query()->find(UserId::Admin->value);
    }
}

if (! function_exists('appUrl')) {
    function appUrl(): string
    {
        return Sanctum::currentApplicationUrlWithPort();
    }
}

if (! function_exists('convertPersianToEnglish')) {
    function convertPersianToEnglish($number): array|string
    {
        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $number
        );
    }
}

if (! function_exists('maskPhoneNumber')) {
    function maskPhoneNumber(string $phoneNumber): string
    {
        return Str::mask($phoneNumber, '*', 2, 6);
    }
}

if (! function_exists('maskCardNumber')) {
    function maskCardNumber(string $cardNumber): string
    {
        return Str::mask($cardNumber, '*', 4, 8);
    }
}

if (! function_exists('maskEmailAddress')) {
    function maskEmailAddress(string $email): string
    {
        $afterMail = Str::after($email, '@');

        return Str::of($email)
            ->before('@')
            ->pipe(fn (string $name) => Str::mask($name, '*', 1))
            .$afterMail;
    }
}

if (! function_exists('isRunningTestsInParallel')) {
    function isRunningTestsInParallel(): bool
    {
        if (
            app()->runningUnitTests() &&
            request()->server('LARAVEL_PARALLEL_TESTING')
        ) {
            return true;
        }

        return app()->runningInConsole() &&
            in_array('--parallel', request()->server('argv'), true);
    }
}
