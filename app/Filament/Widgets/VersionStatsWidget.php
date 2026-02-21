<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Cmsmaxinc\FilamentSystemVersions\Filament\Widgets\DependencyStat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Override;

final class VersionStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    #[Override]
    protected function getStats(): array
    {
        return [
            DependencyStat::make('Nightwatch')
                ->dependency('laravel/nightwatch'),
            DependencyStat::make('Rector')
                ->dependency('driftingly/rector-laravel'),
            DependencyStat::make('PestPHP')
                ->dependency('pestphp/pest'),
        ];
    }
}
