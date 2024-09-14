<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Device;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $label = "Servicio";
    protected static ?string $navigationGroup = 'Administracion de Recursos';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Infomacion General')
                ->description('Informacion general del servicio proporcionado por el usuario.')
                ->icon('heroicon-m-document-text')
                ->schema([
                    Forms\Components\Select::make('type')
                    ->options([
                        'SIVE' => 'SIVE',
                        'Tecnico' => 'Tecnico'
                    ])
                    ->required()
                    ->label('Tipo Servicio'),
                    Forms\Components\TextInput::make('report')
                        ->required()
                        ->maxLength(255)
                        ->label('Reporte'),
                    Forms\Components\DatePicker::make('date_report')
                        ->required()
                        ->label('Fecha'),
                    Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Tecnico'),
                ])->columns(2),
                Section::make('Detalles')
                ->description('Detalles especificos del servicio brindado')
                ->icon('heroicon-s-clipboard-document-list')
                ->schema([
                    Forms\Components\Select::make('employee_id')
                        ->relationship('employee', 'name')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('device_id',null))
                        ->required()
                        ->label('Empleado'),
                    Forms\Components\Select::make('device_id')
                        ->options(fn (Get $get): Collection => Device::query()
                            ->where('employee_id', $get('employee_id'))
                            ->pluck('serial','id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->label('Dispositivo'),
                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->columnSpanFull()
                        ->label('Descripcion'),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->label('Tipo Servicio'),
                Tables\Columns\TextColumn::make('report')
                    ->searchable()
                    ->label('Reporte'),
                Tables\Columns\TextColumn::make('date_report')
                    ->date()
                    ->sortable()
                    ->label('Fecha'),
                Tables\Columns\TextColumn::make('employee.name')
                    ->numeric()
                    ->sortable()
                    ->label('Empleado'),
                Tables\Columns\TextColumn::make('device.serial')
                    ->sortable()
                    ->label('N° Serial'),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->label('Tecnico'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
