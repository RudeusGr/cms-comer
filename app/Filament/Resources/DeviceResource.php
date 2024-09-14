<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages;
use App\Filament\Resources\DeviceResource\RelationManagers;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;
    protected static ?string $label = "Equipo";
    protected static ?string $navigationGroup = 'Administracion de Recursos';
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(255)
                    ->label('Marca'),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255)
                    ->label('Modelo'),
                Forms\Components\TextInput::make('serial')
                    ->required()
                    ->maxLength(255)
                    ->default('S/N')
                    ->label('N° Serie'),
                Forms\Components\Select::make('type')
                    ->options([
                        'Computadora' => 'Computadora',
                        'Celular' => 'Celular'
                    ])
                    ->required()
                    ->label('Tipo Equipo'),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->label('Descripcion'),
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Empleado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand')
                    ->searchable()
                    ->label('Marca'),
                Tables\Columns\TextColumn::make('model')
                    ->searchable()
                    ->label('Modelo'),
                Tables\Columns\TextColumn::make('serial')
                    ->searchable()
                    ->label('N° Serie'),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->label('Tipo Equipo'),
                Tables\Columns\TextColumn::make('employee.name')
                    ->numeric()
                    ->sortable()
                    ->label('Empleado'),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Descripcion'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creado')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Modificado')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
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
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
        ];
    }
}
