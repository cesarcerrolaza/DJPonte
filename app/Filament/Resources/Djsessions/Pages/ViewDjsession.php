<?php

namespace App\Filament\Resources\Djsessions\Pages;

use App\Filament\Resources\Djsessions\DjsessionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDjsession extends ViewRecord
{
    protected static string $resource = DjsessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
