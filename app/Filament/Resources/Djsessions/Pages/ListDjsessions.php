<?php

namespace App\Filament\Resources\Djsessions\Pages;

use App\Filament\Resources\Djsessions\DjsessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDjsessions extends ListRecords
{
    protected static string $resource = DjsessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
