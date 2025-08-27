<?php

namespace App\Filament\Resources\Djs;

use App\Filament\Resources\Djs\RelationManagers\DjsessionsRelationManager;
use App\Filament\Resources\Djs\Pages\CreateDj;
use App\Filament\Resources\Djs\Pages\EditDj;
use App\Filament\Resources\Djs\Pages\ListDjs;
use App\Filament\Resources\Djs\Schemas\DjForm;
use App\Filament\Resources\Djs\Tables\DjsTable;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DjResource extends Resource
{
    protected static ?string $model = User::class;


    protected static string|BackedEnum|null $navigationIcon = Heroicon::MusicalNote;
    protected static ?string $navigationLabel = 'DJs';
    protected static ?string $modelLabel = 'DJ';
    protected static ?string $pluralModelLabel = 'DJs';
    protected static ?string $slug = 'djs';
    protected static string|UnitEnum|null $navigationGroup = 'Gestión de Usuarios';


    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return DjForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DjsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DjsessionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDjs::route('/'),
            'create' => CreateDj::route('/create'),
            'edit' => EditDj::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'dj');
    }
}
