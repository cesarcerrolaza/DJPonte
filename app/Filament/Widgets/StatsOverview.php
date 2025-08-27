<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Djsession;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de DJs', User::where('role', 'dj')->count())
                ->description('DJs registrados en la plataforma')
                ->color('success'),
            Stat::make('Total de Usuarios', User::where('role', 'user')->count())
                ->description('Usuarios (oyentes) registrados')
                ->color('warning'),
            Stat::make('Total de Eventos', Djsession::count())
                ->description('Sesiones creadas en total')
                ->color('info'),
        ];
    }
}
