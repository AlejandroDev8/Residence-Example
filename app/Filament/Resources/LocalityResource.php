<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocalityResource\Pages;
use App\Filament\Resources\LocalityResource\RelationManagers;
use App\Models\Locality;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocalityResource extends Resource
{
    protected static ?string $model = Locality::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office';

    protected static ?string $navigationGroup = 'Zonas';

    protected static ?string $navigationLabel = 'Localidades';

    protected static ?string $modelLabel = 'Localidad';

    protected static ?string $pluralModelLabel = 'Localidades';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('municipality_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cve_loc')
                    ->required()
                    ->maxLength(4),
                Forms\Components\TextInput::make('cve_geo')
                    ->required()
                    ->maxLength(9),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('urban_area'),
                Forms\Components\TextInput::make('lat')
                    ->numeric(),
                Forms\Components\TextInput::make('lng')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('municipality.name')
                    ->label('Municipio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cve_loc')
                    ->label('Clave de Localidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cve_geo')
                    ->label('Clave Geoestadística')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                // Tables\Columns\IconColumn::make('urban_area')
                //     ->boolean(),
                Tables\Columns\TextColumn::make('lat')
                    ->label('Latitud')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lng')
                    ->label('Longitud')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('municipality_id')
                    ->label('Municipio')
                    ->relationship('municipality', 'name'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocalities::route('/'),
            'create' => Pages\CreateLocality::route('/create'),
            // 'edit' => Pages\EditLocality::route('/{record}/edit'),
        ];
    }
}
