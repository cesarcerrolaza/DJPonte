<?php

namespace App\Filament\Resources\Djs\Pages;

use App\Filament\Resources\Djs\DjResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDj extends EditRecord
{
    protected static string $resource = DjResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
