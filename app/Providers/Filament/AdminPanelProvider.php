<?php

namespace App\Providers\Filament;

use AlizHarb\ActivityLog\ActivityLogPlugin;
use Andreia\FilamentNordTheme\FilamentNordThemePlugin;
use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use Arshaviras\WeatherWidget\Widgets\WeatherWidget;
use Awcodes\Overlook\OverlookPlugin;
use Awcodes\Overlook\Widgets\OverlookWidget;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Cmsmaxinc\FilamentSystemVersions\Filament\Widgets\SystemInfoWidget;
use Cmsmaxinc\FilamentSystemVersions\FilamentSystemVersionsPlugin;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Moataz01\FilamentNotificationSound\FilamentNotificationSoundPlugin;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use SalmanAlmajali\JokesWidget\JokesWidget;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use Statikbe\FilamentTranslationManager\FilamentChainedTranslationManagerPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use Tapp\FilamentMailLog\FilamentMailLogPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        $this->configurePanelSwitch();
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa(hasPrefetching: true)
            ->brandName('Corekit Laravel')
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            // ->strictAuthorization()
            ->maxContentWidth(Width::MaxContent)
            ->globalSearchKeyBindings(['command+i', 'ctrl+i'])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                SystemInfoWidget::class,
                OverlookWidget::class,
                JokesWidget::class,
                WeatherWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationItems($this->getNavItems())
            ->plugins([
                FilamentNordThemePlugin::make(),
                FilamentBackgroundsPlugin::make(),
                EnvironmentIndicatorPlugin::make()
                    ->showGitBranch()
                    ->showDebugModeWarning(),
                FilamentSpatieLaravelHealthPlugin::make(),
                FilamentSpatieLaravelBackupPlugin::make(),
                FilamentExceptionsPlugin::make()
                    ->navigationLabel('Error Logs')
                    ->navigationIcon('heroicon-o-bug-ant')
                    ->navigationBadge()
                    ->navigationGroup('System')
                    ->modelPruneInterval(Carbon::now()->subDays(7)),
                ActivityLogPlugin::make()
                    ->label('Log')
                    ->pluralLabel('Logs')
                    ->navigationGroup('System'),
                FilamentNotificationSoundPlugin::make(),
                FilamentUiSwitcherPlugin::make()
                    ->withModeSwitcher(),
                FilamentLanguageSwitcherPlugin::make()
                    ->locales(['en', 'fa'])
                    ->rememberLocale()
                    ->showOnAuthPages(),
                FilamentEnvEditorPlugin::make()
                    ->navigationGroup('System')
                    ->navigationLabel('My Env')
                    ->navigationIcon('heroicon-o-cog-8-tooth')
                    ->navigationSort(1)
                    ->slug('env-editor'),
                FilamentMailLogPlugin::make(),
                FilamentAuthenticationLogPlugin::make(),
                FilamentSystemVersionsPlugin::make()
                    ->navigationLabel('System Info')
                    ->navigationGroup('System')
                    ->navigationIcon('heroicon-o-cpu-chip') // Or use Enum
                    ->navigationSort(10),
                GlobalSearchModalPlugin::make(),
                FilamentApexChartsPlugin::make(),
                OverlookPlugin::make()
                    ->sort(1)
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 3,
                        'lg' => 4,
                        'xl' => 5,
                        '2xl' => null,
                    ]),
                SpotlightPlugin::make(),
                FilamentChainedTranslationManagerPlugin::make(),
            ]);
    }

    public function configurePanelSwitch(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch): void {
            $panelSwitch
                ->panels([
                    'admin',
                    'user',
                ])
                ->modalWidth('sm')
                ->slideOver()
                ->icons([
                    'admin' => 'heroicon-o-square-2-stack',
                    'user' => 'heroicon-o-star',
                ])
                ->iconSize(16)
                ->labels([
                    'admin' => 'Admin Panel',
                    'user' => 'User Panel',
                ]);
        });
    }

    private function getNavItems(): array
    {
        return once(
            fn () => collect(config('tools'))->except('backend-admin')
                ->map(
                    fn (array $tool): NavigationItem => NavigationItem::make()
                        ->label(fn (): string => trans(Arr::get($tool, 'title')))
                        ->url(Arr::get($tool, 'url'), shouldOpenInNewTab: true)
                        ->icon(Arr::get($tool, 'heroicon'))
                        ->group(Arr::get($tool, 'group'))
                        ->sort(Arr::get($tool, 'sort'))
                )
                ->all()
        );
    }
}
