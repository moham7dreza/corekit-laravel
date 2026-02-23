<?php

namespace App\Providers;

use App\Console\Commands\System\DataMigrationCommand;
use App\Enums\UserPermission;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Pulse\Facades\Pulse;
use Override;
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
    #[Override]
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureGate();
        $this->configurePulse();
        $this->configureHealthChecks();
        $this->configureCommands();
        $this->configureModel();
        $this->configureVite();
        $this->loadExtraMigrationsPath();
        $this->configurePassword();
        $this->configureCommandsToRunOnReload();
        $this->configureQueue();
        $this->configureEloquentRelation();
        $this->configureDate();
    }

    private function configureHealthChecks(): void
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

    private function configureGate(): void
    {
        Gate::define('viewPulse', fn (User $user): bool => $user->checkPermissionTo(UserPermission::SeePanel));
    }

    private function configurePulse(): void
    {
        Pulse::user(fn ($user): array => [
            'name' => $user->name,
            'extra' => $user->email,
            'avatar' => $user->avatar,
        ]);
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());

        DumpCommand::prohibit(app()->isProduction());
    }

    private function configureModel(): void
    {
        Model::automaticallyEagerLoadRelationships();

        Model::shouldBeStrict(! app()->isProduction());

        Model::unguard();
    }

    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    private function loadExtraMigrationsPath(): void
    {
        if (! app()->runningUnitTests()) {
            $this->loadMigrationsFrom(__DIR__.'/../..'.DataMigrationCommand::PATH);
        }
    }

    private function configurePassword(): void
    {
        Password::defaults(
            static fn () => Password::min(8) // Minimum length of 8 characters
                ->mixedCase() // Must include both uppercase and lowercase letters
                ->letters()   // Must include at least one letter
                ->numbers()   // Must include at least one number
                ->symbols()   // Must include at least one symbol
                ->uncompromised(), // Checks against known data breaches
        );
    }

    private function configureCommandsToRunOnReload(): void
    {
        // $this->reloads('permission:cache-reset');
    }

    private function configureQueue(): void
    {
        Queue::withoutInterruptionPolling();
    }

    private function configureEloquentRelation(): void
    {
        Relation::enforceMorphMap([
            'user' => User::class,
        ]);
    }

    private function configureDate(): void
    {
        Date::use(CarbonImmutable::class);

        Date::macro('createFromTimestampLocal', static fn ($timestamp) => Date::createFromTimestamp($timestamp, config()->string('app.timezone')));
    }
}
