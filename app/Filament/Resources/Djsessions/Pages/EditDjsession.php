<?php

namespace App\Filament\Resources\Djsessions\Pages;

use App\Filament\Resources\Djsessions\DjsessionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDjsession extends EditRecord
{
    protected static string $resource = DjsessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
