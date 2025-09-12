<?php

namespace App\Filament\Resources\MaizeSampleResource\Pages;

use App\Filament\Resources\MaizeSampleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewMaizeSample extends ViewRecord
{
  protected static string $resource = MaizeSampleResource::class;

  public function getHeading(): string
  {
    $n = $this->record->sample_number ?? $this->record->id;
    return "Muestra #{$n}";
  }

  public function getTitle(): string
  {
    return $this->getHeading();
  }

  protected function getHeaderActions(): array
  {
    return [
      Actions\EditAction::make(),
      Actions\DeleteAction::make(),
    ];
  }

  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist->schema([
      Section::make('Datos generales')
        ->schema([
          Grid::make(3)->schema([
            TextEntry::make('code')->label('Código')->placeholder('—'),
            TextEntry::make('sample_number')->label('N° Muestra'),
            TextEntry::make('collection_date')->label('Fecha de colecta')->date('d-M-Y'),
          ]),
        ]),

      Section::make('Ubicación')
        ->schema([
          Grid::make(3)->schema([
            TextEntry::make('municipality.name')->label('Municipio'),
            TextEntry::make('locality.name')->label('Comunidad'),
            TextEntry::make('coordinates')
              ->label('Coordenadas')
              ->state(function ($record) {
                $lat = $record->latitude;
                $lng = $record->longitude;
                return ($lat !== null && $lng !== null) ? "{$lat}, {$lng}" : '—';
              }),
          ]),
        ]),

      Section::make('Responsables')
        ->schema([
          Grid::make(2)->schema([
            TextEntry::make('collector.name')->label('Recolector'),
            TextEntry::make('farmer.name')->label('Agricultor')->placeholder('—'),
          ]),
        ]),

      Section::make('Variedad / Notas')
        ->schema([
          Grid::make(1)->schema([
            TextEntry::make('variety_name')->label('Variedad')->placeholder('—'),
            TextEntry::make('notes')->label('Notas')->markdown()->placeholder('—'),
          ]),
        ]),

      Section::make('Sub-muestras')
        ->collapsible()
        ->collapsed()
        ->schema([
          RepeatableEntry::make('subsamples')
            ->label('Sub-muestras')
            ->schema([
              // === Imagen de la sub-muestra (usa tu accessor image_url) ===
              ImageEntry::make('image_url')
                ->label('Foto')
                ->height('200px')
                ->hidden(fn($state) => blank($state))
                ->columnSpanFull(),

              Grid::make(4)->schema([
                TextEntry::make('subsample_number')->label('N°'),
                TextEntry::make('color_grano')->label('Color grano')->placeholder('—'),
                TextEntry::make('tipo_grano')->label('Tipo')->placeholder('—'),
                TextEntry::make('arreglo_hileras_grano')->label('Arreglo hileras')->placeholder('—'),
              ]),
              Grid::make(4)->schema([
                TextEntry::make('diametro_mazorca_mm')->label('Ø mazorca (mm)')->placeholder('—'),
                TextEntry::make('largo_mazorca_mm')->label('Largo mazorca (mm)')->placeholder('—'),
                TextEntry::make('peso_mazorca_g')->label('Peso mazorca (g)')->placeholder('—'),
                TextEntry::make('peso_grano_50_g')->label('Peso 50 granos (g)')->placeholder('—'),
              ]),
              Grid::make(4)->schema([
                TextEntry::make('num_hileras')->label('N° hileras')->placeholder('—'),
                TextEntry::make('num_granos_por_hilera')->label('N° granos/hilera')->placeholder('—'),
                TextEntry::make('ancho_grano_mm')->label('AGR (mm)')->placeholder('—'),
                TextEntry::make('longitud_grano_mm')->label('LGR (mm)')->placeholder('—'),
              ]),
              Grid::make(4)->schema([
                TextEntry::make('grosor_grano_mm')->label('Grosor (mm)')->placeholder('—'),
                TextEntry::make('indice_lgr_agr')->label('Índice LGR/AGR')->placeholder('—'),
                TextEntry::make('volumen_grano_50_ml')->label('Volumen 50 (ml)')->placeholder('—'),
                TextEntry::make('color_endospermo_grano')->label('Color endospermo')->placeholder('—'),
              ]),
              Grid::make(3)->schema([
                TextEntry::make('color_olote')->label('Color olote')->placeholder('—'),
                TextEntry::make('color_dorsal_grano')->label('Color dorsal')->placeholder('—'),
                TextEntry::make('forma_corona_grano')->label('Forma corona')->placeholder('—'),
              ]),
            ])
            ->columns(1)
            ->columnSpanFull(),
        ]),
    ]);
  }
}
