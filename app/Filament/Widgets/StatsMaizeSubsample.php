<?php

namespace App\Filament\Widgets;

use App\Models\MaizeSample;
use App\Models\MaizeSubSample;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsMaizeSubsample extends BaseWidget
{
    protected function getStats(): array
    {
        $totalMaizeSubsamples = MaizeSubSample::all()->count();
        $totalMaizeSamples = MaizeSample::all()->count();

        return [
            Stat::make('Total de Muestras de Maíz', $totalMaizeSamples)
                ->icon('heroicon-s-beaker')
                ->color('primary'),
            Stat::make('Total de Sub-muestras de Maíz', $totalMaizeSubsamples)
                ->icon('heroicon-s-beaker')
                ->color('primary'),
        ];
    }
}
