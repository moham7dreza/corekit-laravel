<?php

namespace Modules\Filament\Resources\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Filament\Resources\UserResource;
use Override;
use pxlrbt\FilamentExcel\Actions\ExportAction;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            ExportAction::make(),
        ];
    }
}
