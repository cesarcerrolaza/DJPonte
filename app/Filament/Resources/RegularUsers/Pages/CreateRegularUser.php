<?php

namespace App\Filament\Resources\RegularUsers\Pages;

use App\Filament\Resources\RegularUsers\RegularUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegularUser extends CreateRecord
{
    protected static string $resource = RegularUserResource::class;
}
