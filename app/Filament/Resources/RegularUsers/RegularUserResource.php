<?php

namespace App\Filament\Resources\RegularUsers;

use App\Filament\Resources\RegularUsers\Pages\CreateRegularUser;
use App\Filament\Resources\RegularUsers\Pages\EditRegularUser;
use App\Filament\Resources\RegularUsers\Pages\ListRegularUsers;
use App\Filament\Resources\RegularUsers\Schemas\RegularUserForm;
use App\Filament\Resources\RegularUsers\Tables\RegularUsersTable;
use App\Models\User as User;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegularUserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?string $slug = 'usuarios';
    protected static string|UnitEnum|null $navigationGroup = 'Gestión de Usuarios';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return RegularUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegularUsersTable::configure($table);
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
            'index' => ListRegularUsers::route('/'),
            'create' => CreateRegularUser::route('/create'),
            'edit' => EditRegularUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'user');
    }
}
