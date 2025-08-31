<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MunicipalityResource\Pages;
use App\Filament\Resources\MunicipalityResource\RelationManagers;
use App\Models\Municipality;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MunicipalityResource extends Resource
{
    protected static ?string $model = Municipality::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2';

    protected static ?string $navigationGroup = 'Zonas';

    protected static ?string $navigationLabel = 'Municipios';

    protected static ?string $modelLabel = 'Municipio';

    protected static ?string $pluralModelLabel = 'Municipios';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('state_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cve_mun')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('cve_geo')
                    ->required()
                    ->maxLength(5),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(150),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('state.name')
                    ->label('Estado')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cve_mun')
                    ->label('Clave de Municipio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cve_geo')
                    ->label('Clave Geoestadística')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('state_id')
                    ->label('Estado')
                    ->relationship('state', 'name'),
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
            'index' => Pages\ListMunicipalities::route('/'),
            'create' => Pages\CreateMunicipality::route('/create'),
            'edit' => Pages\EditMunicipality::route('/{record}/edit'),
        ];
    }
}
