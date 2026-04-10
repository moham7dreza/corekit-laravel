<?php

declare(strict_types=1);

namespace Modules\Auth\Providers;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Database\Seeders\AuthSeeder;
use Override;

final class AuthServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->setupSeeders();
    }

    public function boot(): void
    {
        $this->configureRateLimiter();
    }

    private function setupSeeders(): void
    {
        DatabaseSeeder::$seeders[] = AuthSeeder::class;
    }

    public function configureRateLimiter(): void
    {
        RateLimiter::for('otp-request', static fn (Request $request): array => [
            Limit::perMinutes(2, 5)
                ->by($request->input('mobile') ?: $request->ip()),
        ]);
    }
}
