<?php

namespace App\Filament\Resources\Djsessions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DjsessionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('name'),
                TextEntry::make('description'),
                TextEntry::make('venue'),
                TextEntry::make('address'),
                TextEntry::make('city'),
                ImageEntry::make('image'),
                IconEntry::make('active')
                    ->boolean(),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('start_time')
                    ->dateTime(),
                TextEntry::make('end_time')
                    ->dateTime(),
                TextEntry::make('song_request_timeout')
                    ->numeric(),
                TextEntry::make('current_users')
                    ->numeric(),
                TextEntry::make('peak_users')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
