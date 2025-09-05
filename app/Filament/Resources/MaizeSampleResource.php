<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaizeSampleResource\Pages;
use App\Models\Locality;
use App\Models\MaizeSample;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class MaizeSampleResource extends Resource
{
    protected static ?string $model = MaizeSample::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Agronomía';
    protected static ?string $navigationLabel = 'Muestras de Maíz';
    protected static ?string $modelLabel = 'Muestra de Maíz';
    protected static ?string $pluralModelLabel = 'Muestras de Maíz';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos de la muestra')
                    ->schema([
                        TextInput::make('code')
                            ->label('Código')
                            ->maxLength(50),

                        TextInput::make('sample_number')
                            ->label('N° Muestra')
                            ->numeric()
                            ->required(),

                        // Recolector (users)
                        Select::make('user_id')
                            ->label('Recolector')
                            ->relationship('collector', 'name') // MaizeSample::collector()->belongsTo(User::class, 'user_id')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // Agricultor (farmers)
                        Select::make('farmer_id')
                            ->label('Agricultor')
                            ->relationship('farmer', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        // Municipio
                        Select::make('municipality_id')
                            ->label('Municipio')
                            ->relationship('municipality', 'name')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),

                        // Comunidad dependiente del municipio
                        Select::make('locality_id')
                            ->label('Comunidad')
                            // Opciones filtradas por el municipio seleccionado
                            ->options(fn(Get $get) => ($get('municipality_id'))
                                ? Locality::query()
                                ->where('municipality_id', $get('municipality_id'))
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray()
                                : [])
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn(Get $get) => blank($get('municipality_id')))
                            ->reactive(),

                        DatePicker::make('collection_date')
                            ->label('Fecha de colecta'),

                        TextInput::make('latitude')
                            ->label('Latitud (N)')
                            ->numeric()
                            ->step('0.000001'),

                        TextInput::make('longitude')
                            ->label('Longitud (O)')
                            ->numeric()
                            ->step('0.000001'),

                        TextInput::make('variety_name')
                            ->label('Nombre de la variedad'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3),
                    ])
                    ->columns(3),

                Section::make('Sub-muestras')
                    ->description('Captura una o varias sub-muestras con sus mediciones.')
                    ->schema([
                        HasManyRepeater::make('subsamples')
                            ->label('Sub-muestras')
                            ->relationship('subsamples') // MaizeSample::subsamples()
                            ->defaultItems(1)
                            ->orderable(false)
                            ->addActionLabel('Agregar sub-muestra')
                            ->schema([
                                TextInput::make('subsample_number')
                                    ->label('N° Submuestra')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(),

                                // Categóricos
                                TextInput::make('color_grano')->label('Color de grano')
                                    ->datalist(['BLANCO', 'AMARILLO', 'ROJO', 'MORADO', 'AZUL', 'NEGRO']),
                                TextInput::make('color_olote')->label('Color de olote')
                                    ->datalist(['BLANCO', 'ROSA', 'ROJO', 'CAFÉ']),
                                TextInput::make('tipo_grano')->label('Tipo de grano')
                                    ->datalist(['Dentado', 'Duro', 'Reventador', 'Harinoso']),
                                TextInput::make('forma_corona_grano')->label('Forma de la corona'),
                                TextInput::make('color_dorsal_grano')->label('Color dorsal'),
                                TextInput::make('color_endospermo_grano')->label('Color del endospermo'),
                                TextInput::make('arreglo_hileras_grano')->label('Arreglo de hileras'),

                                // Métricos
                                TextInput::make('diametro_mazorca_mm')->label('Diámetro mazorca (mm)')
                                    ->numeric()->minValue(0),
                                TextInput::make('largo_mazorca_mm')->label('Largo mazorca (mm)')
                                    ->numeric()->minValue(0),
                                TextInput::make('peso_mazorca_g')->label('Peso de mazorca (g)')
                                    ->numeric()->minValue(0),
                                TextInput::make('peso_grano_50_g')->label('Peso de grano 50 (g)')
                                    ->numeric()->minValue(0),
                                TextInput::make('num_hileras')->label('N° de hileras')
                                    ->numeric()->minValue(0),
                                TextInput::make('num_granos_por_hilera')->label('N° granos/hilera')
                                    ->numeric()->minValue(0),
                                TextInput::make('grosor_grano_mm')->label('Grosor grano (mm)')
                                    ->numeric()->minValue(0),
                                TextInput::make('ancho_grano_mm')->label('AGR (mm)')
                                    ->numeric()->minValue(0),
                                TextInput::make('longitud_grano_mm')->label('LGR (mm)')
                                    ->numeric()->minValue(0),

                                // Se calcula en el modelo si no lo mandas
                                TextInput::make('indice_lgr_agr')->label('Índice LGR/AGR')
                                    ->disabled()
                                    ->helperText('Se calcula automáticamente al guardar si LGR y AGR están capturados.'),

                                TextInput::make('volumen_grano_50_ml')->label('Volumen 50 semillas (ml)')
                                    ->numeric()->minValue(0),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sample_number')
                    ->label('Muestra')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('collector.name')
                    ->label('Recolector')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('farmer.name')
                    ->label('Agricultor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('municipality.name')
                    ->label('Municipio')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('locality.name')
                    ->label('Comunidad')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('collection_date')
                    ->label('Fecha')
                    ->date('d-M-Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subsamples_count')
                    ->label('# Sub-muestras')
                    ->counts('subsamples')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('municipality_id')
                    ->label('Municipio')
                    ->relationship('municipality', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('locality_id')
                    ->label('Comunidad')
                    ->relationship('locality', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('user_id')
                    ->label('Recolector')
                    ->relationship('collector', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('farmer_id')
                    ->label('Agricultor')
                    ->relationship('farmer', 'name')
                    ->preload()
                    ->searchable(),

                Filter::make('collection_date')
                    ->label('Fecha de colecta')
                    ->form([
                        DatePicker::make('from')->label('Desde'),
                        DatePicker::make('until')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn(Builder $q, $date) => $q->whereDate('collection_date', '>=', $date))
                            ->when($data['until'] ?? null, fn(Builder $q, $date) => $q->whereDate('collection_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        // Si prefieres editar sub-muestras en una página separada,
        // puedes crear un RelationManager para 'subsamples' y agregarlo aquí.
        return [
            // MaizeSubsamplesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMaizeSamples::route('/'),
            'create' => Pages\CreateMaizeSample::route('/create'),
            'edit'   => Pages\EditMaizeSample::route('/{record}/edit'),
            'view'   => Pages\ViewMaizeSample::route('/{record}'),
        ];
    }
}
