<?php

namespace App\Filament\Widgets;

use App\Models\Farmer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsFarmers extends BaseWidget
{
    protected function getStats(): array
    {
        $totalFarmers = Farmer::all()->count();

        return [
            // Stat::make('Total de Agricultores', $totalFarmers)
            //     ->icon('heroicon-s-user-group')
            //     // ->description('Número total de agricultores registrados en el sistema.')
            //     // ->descriptionIcon('heroicon-s-user-group')
            //     ->color('primary'),
        ];
    }
}
