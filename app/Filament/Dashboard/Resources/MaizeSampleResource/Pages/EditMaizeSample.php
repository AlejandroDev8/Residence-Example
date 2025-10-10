<?php

namespace App\Filament\Dashboard\Resources\MaizeSampleResource\Pages;

use App\Filament\Dashboard\Resources\MaizeSampleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaizeSample extends EditRecord
{
    protected static string $resource = MaizeSampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
