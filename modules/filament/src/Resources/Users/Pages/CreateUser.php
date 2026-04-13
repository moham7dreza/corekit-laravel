<?php

namespace Modules\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
