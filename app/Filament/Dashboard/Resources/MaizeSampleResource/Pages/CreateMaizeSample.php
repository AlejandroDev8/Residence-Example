<?php

namespace App\Filament\Dashboard\Resources\MaizeSampleResource\Pages;

use App\Filament\Dashboard\Resources\MaizeSampleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMaizeSample extends CreateRecord
{
    protected static string $resource = MaizeSampleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // devuelve null si no hay usuario, evita ->id en null
        $userId = Auth::id();

        if (! $userId) {
            throw new \RuntimeException('Usuario no autenticado al crear el registro.');
        }

        $data['user_id'] = $userId;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Muestra de maíz creada')
            ->body('La muestra de maíz ha sido creada exitosamente.');
    }
}
