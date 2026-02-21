<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\Health\Checks\Checks\BackupsCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DatabaseSizeCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\HorizonCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\RedisMemoryUsageCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->runHealthChecks();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    private function runHealthChecks(): void
    {
        if ($this->app->runningUnitTests()) {
            return;
        }

        Health::checks([
            DatabaseCheck::new(),

            CacheCheck::new(),

            QueueCheck::new(),

            RedisCheck::new(),

            BackupsCheck::new(),

            EnvironmentCheck::new()
                ->expectEnvironment(config()->string('app.env')),

            DatabaseTableSizeCheck::new()
                ->table('users', maxSizeInMb: 1_000),

            DatabaseSizeCheck::new()
                ->failWhenSizeAboveGb(errorThresholdGb: 0.1),

            DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(50)
                ->failWhenMoreConnectionsThan(100),

            DebugModeCheck::new(),

            OptimizedAppCheck::new(),

            PingCheck::new()
                ->url(config()->string('app.url'))
                ->timeout(2)
                ->retryTimes(3)
                ->label('App'),

            HorizonCheck::new(),

            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(90)
                ->failWhenUsedSpaceIsAbovePercentage(95),

            ScheduleCheck::new()
                ->heartbeatMaxAgeInMinutes(2),

            RedisMemoryUsageCheck::new()
                ->warnWhenAboveMb(900)
                ->failWhenAboveMb(1000),
        ]);
    }
}
