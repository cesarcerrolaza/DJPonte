<?php

namespace App\Filament\Resources\Djsessions;

use App\Filament\Resources\Djsessions\Pages\CreateDjsession;
use App\Filament\Resources\Djsessions\Pages\EditDjsession;
use App\Filament\Resources\Djsessions\Pages\ListDjsessions;
use App\Filament\Resources\Djsessions\Pages\ViewDjsession;
use App\Filament\Resources\Djsessions\Schemas\DjsessionForm;
use App\Filament\Resources\Djsessions\Schemas\DjsessionInfolist;
use App\Filament\Resources\Djsessions\Tables\DjsessionsTable;
use App\Models\Djsession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DjsessionResource extends Resource
{
    protected static ?string $model = Djsession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cake;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return DjsessionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DjsessionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DjsessionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDjsessions::route('/'),
            'create' => CreateDjsession::route('/create'),
            'view' => ViewDjsession::route('/{record}'),
            'edit' => EditDjsession::route('/{record}/edit'),
        ];
    }
}
