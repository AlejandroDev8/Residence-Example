<?php

namespace App\Filament\Dashboard\Resources\MaizeSampleResource\Pages;

use App\Filament\Dashboard\Resources\MaizeSampleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditMaizeSample extends EditRecord
{
    protected static string $resource = MaizeSampleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (!Auth::check()) {
            abort(403, 'Usuario no autenticado');
        }

        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return  Notification::make()
            ->success()
            ->title('Muestra de maíz actualizada')
            ->body('La muestra de maíz ha sido actualizada exitosamente.');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
