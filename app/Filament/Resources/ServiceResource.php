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
    protected static ?string $navigationGroup = 'Resource Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                ->description('General information about the service performed by the user')
                ->icon('heroicon-m-document-text')
                ->schema([
                    Forms\Components\Select::make('type')
                    ->options([
                        'SIVE' => 'SIVE',
                        'Tecnico' => 'Tecnico'
                    ])
                    ->required(),
                    Forms\Components\TextInput::make('report')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('date_report')
                        ->required(),
                    Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                ])->columns(2),
                Section::make('Details')
                ->description('Specific details of the service that was performed')
                ->icon('heroicon-s-clipboard-document-list')
                ->schema([
                    Forms\Components\Select::make('employee_id')
                        ->relationship('employee', 'name')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('device_id',null))
                        ->required(),
                    Forms\Components\Select::make('device_id')
                        ->options(fn (Get $get): Collection => Device::query()
                            ->where('employee_id', $get('employee_id'))
                            ->pluck('serial','id'))
                        ->searchable()
                        ->preload()
                        ->live(),
                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('report')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_report')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('device.serial')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
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
