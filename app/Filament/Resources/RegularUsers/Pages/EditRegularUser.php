<?php

namespace App\Filament\Resources\RegularUsers\Pages;

use App\Filament\Resources\RegularUsers\RegularUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegularUser extends EditRecord
{
    protected static string $resource = RegularUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
