<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\UserPanelProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    UserPanelProvider::class,
    FortifyServiceProvider::class,
    HorizonServiceProvider::class,
    TelescopeServiceProvider::class,
];
