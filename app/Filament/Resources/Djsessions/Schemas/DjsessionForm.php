<?php

namespace App\Filament\Resources\Djsessions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DjsessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->default(null),
                TextInput::make('venue')
                    ->required(),
                TextInput::make('address')
                    ->default(null),
                TextInput::make('city')
                    ->default(null),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('active')
                    ->required(),
                TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('start_time'),
                DateTimePicker::make('end_time'),
                TextInput::make('song_request_timeout')
                    ->required()
                    ->numeric()
                    ->default(30),
                TextInput::make('current_users')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('peak_users')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
