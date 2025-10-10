<?php

namespace App\Filament\Dashboard\Resources\MaizeSampleResource\Pages;

use App\Filament\Dashboard\Resources\MaizeSampleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaizeSamples extends ListRecords
{
    protected static string $resource = MaizeSampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
