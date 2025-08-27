<?php

namespace App\Filament\Resources\RegularUsers\Pages;

use App\Filament\Resources\RegularUsers\RegularUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegularUsers extends ListRecords
{
    protected static string $resource = RegularUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
