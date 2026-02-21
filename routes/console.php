<?php

use Cmsmaxinc\FilamentSystemVersions\Commands\CheckDependencyVersions;
use Illuminate\Support\Facades\Schedule;

Schedule::command('telescope:prune')->daily();

Schedule::command('horizon:snapshot')->everyFiveMinutes();

Schedule::command(CheckDependencyVersions::class)->daily();
