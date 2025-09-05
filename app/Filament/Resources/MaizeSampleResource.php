<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaizeSampleResource\Pages;
use App\Models\Locality;
use App\Models\MaizeSample;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
                    ->description('Captura los datos generales de la muestra de maíz.')
                    ->schema([
                        Fieldset::make('Identificación')
                            ->schema([
                                TextInput::make('code')
                                    ->label('Código')
                                    ->required()
                                    ->maxLength(50),

                                TextInput::make('sample_number')
                                    ->label('N° Muestra')
                                    ->numeric()
                                    ->required(),
                            ]),

                        Fieldset::make('Recolector y Agricultor')
                            ->schema([
                                Select::make('user_id')
                                    ->label('Recolector')
                                    ->relationship('collector', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('farmer_id')
                                    ->label('Agricultor')
                                    ->relationship('farmer', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                            ]),

                        Fieldset::make('Ubicación')
                            ->schema([
                                Select::make('municipality_id')
                                    ->label('Municipio')
                                    ->relationship('municipality', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('locality_id', null);
                                    }),

                                Select::make('locality_id')
                                    ->label('Comunidad')
                                    ->options(fn(Get $get): Collection => Locality::query()->where('municipality_id', $get('municipality_id'))->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required(),

                                TextInput::make('latitude')
                                    ->label('Latitud (N)')
                                    ->required()
                                    ->numeric()
                                    ->step('0.000001'),

                                TextInput::make('longitude')
                                    ->label('Longitud (O)')
                                    ->required()
                                    ->numeric()
                                    ->step('0.000001'),
                            ]),

                        Fieldset::make('Detalles de la muestra')
                            ->schema([
                                DatePicker::make('collection_date')
                                    ->format('d-M-Y')
                                    ->displayFormat('d-M-Y')
                                    ->timezone('America/Mexico_City')
                                    ->native(false)
                                    ->required()
                                    ->label('Fecha de colecta')
                                    ->columnSpan(1),

                                TextInput::make('variety_name')
                                    ->label('Nombre de la variedad')
                                    ->required()
                                    ->columnSpan(1),

                                Textarea::make('notes')
                                    ->label('Notas')
                                    ->autosize()
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                            ])
                    ])
                    ->columns(3),

                Section::make('Sub-muestras')
                    ->description('Captura una o varias sub-muestras con sus mediciones.')
                    ->schema([
                        Repeater::make('subsamples')
                            ->label('Sub-muestras')
                            ->relationship('subsamples') // MaizeSample::subsamples()
                            ->defaultItems(1)
                            ->addActionLabel('Agregar sub-muestra')
                            ->schema([
                                Fieldset::make('Identificación')->schema([
                                    TextInput::make('subsample_number')
                                        ->label('N° Submuestra')
                                        ->numeric()
                                        ->minValue(0)
                                        ->required(),
                                ]),

                                // Categóricos
                                Fieldset::make('Categóricos')
                                    ->schema([
                                        Select::make('color_grano')
                                            ->label('Color de grano')
                                            ->options([
                                                'BLANCO',
                                                'AMARILLO',
                                                'ROJO',
                                                'MORADO',
                                                'AZUL',
                                                'NEGRO'
                                            ]),
                                        Select::make('color_olote')
                                            ->label('Color de olote')
                                            ->options([
                                                'BLANCO',
                                                'ROSA',
                                                'ROJO',
                                                'CAFÉ'
                                            ]),
                                        Select::make('tipo_grano')
                                            ->label('Tipo de grano')
                                            ->options([
                                                'Dentado',
                                                'Duro',
                                                'Reventador',
                                                'Harinoso'
                                            ]),
                                        TextInput::make('forma_corona_grano')
                                            ->label('Forma de la corona')
                                            ->helperText('Puntiaguda, redondeada, etc.'),
                                        TextInput::make('color_dorsal_grano')
                                            ->label('Color dorsal')
                                            ->helperText('Color del lado convexo del grano.'),
                                        TextInput::make('color_endospermo_grano')
                                            ->label('Color del endospermo')
                                            ->helperText('Color del interior del grano.'),
                                        TextInput::make('arreglo_hileras_grano')
                                            ->label('Arreglo de hileras')
                                            ->helperText('Regular, irregular, etc.')
                                            ->columnSpanFull(),
                                    ]),

                                // Métricos
                                Fieldset::make('Métricas')
                                    ->schema([
                                        TextInput::make('diametro_mazorca_mm')
                                            ->label('Diámetro mazorca (mm)')
                                            ->numeric()->minValue(0),
                                        TextInput::make('largo_mazorca_mm')
                                            ->label('Largo mazorca (mm)')
                                            ->numeric()->minValue(0),
                                        TextInput::make('peso_mazorca_g')
                                            ->label('Peso de mazorca (g)')
                                            ->numeric()->minValue(0),
                                        TextInput::make('peso_grano_50_g')
                                            ->label('Peso de grano 50 (g)')
                                            ->numeric()->minValue(0),
                                        TextInput::make('num_hileras')
                                            ->label('N° de hileras')
                                            ->numeric()->minValue(0),
                                        TextInput::make('num_granos_por_hilera')
                                            ->label('N° granos/hilera')
                                            ->numeric()->minValue(0),
                                        TextInput::make('grosor_grano_mm')
                                            ->label('Grosor grano (mm)')
                                            ->numeric()->minValue(0),
                                        TextInput::make('ancho_grano_mm')
                                            ->label('AGR (mm)')
                                            ->numeric()->minValue(0),
                                        TextInput::make('longitud_grano_mm')
                                            ->label('LGR (mm)')
                                            ->numeric()->minValue(0),
                                    ])
                                    ->columns(3),

                                // Se calcula en el modelo si no lo mandas
                                Fieldset::make('Cálculos')
                                    ->schema([
                                        TextInput::make('indice_lgr_agr')
                                            ->label('Índice LGR/AGR')
                                            ->disabled()
                                            ->helperText('Se calcula automáticamente al guardar si LGR y AGR están capturados.'),

                                        TextInput::make('volumen_grano_50_ml')
                                            ->label('Volumen 50 semillas (ml)')
                                            ->numeric()
                                            ->minValue(0),
                                    ])
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
