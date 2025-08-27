<?php

namespace App\Filament\Resources\Djs\RelationManagers;

use App\Filament\Resources\Djsessions\DjsessionResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class DjsessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'djsessions';
    protected static ?string $inverseRelationship = 'dj'; // Relación inversa en el modelo Djsession

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label('Nombre del Evento'),
                IconColumn::make('is_active')->label('Activo')->boolean(),
                TextColumn::make('start_time')->label('Inicio')->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                CreateAction::make()->label('Nueva Sesión'),
                Action::make('verTodas')
                    ->label('Ver todas las sesiones')
                    ->url(route('filament.admin.resources.djsessions.index'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->openUrlInNewTab(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false; // Permite acciones de edición y eliminación
    }
}
