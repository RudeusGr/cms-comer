<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class DevicesRelationManager extends RelationManager
{
    protected static ?string $label = 'Equipo';
    protected static ?string $title = 'Equipo';
    protected static string $relationship = 'device';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(255)
                    ->Label('Marca'),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255)
                    ->Label('Modelo'),
                Forms\Components\TextInput::make('serial')
                    ->required()
                    ->maxLength(255)
                    ->default('S/N')
                    ->Label('N° Serie'),
                Forms\Components\Select::make('type')
                    ->options([
                        'Computadora' => 'Computadora',
                        'Celular' => 'Celular'
                    ])
                    ->required()
                    ->Label('Tipo Equipo'),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->Label('Descripcion'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('brand')
            ->columns([
                Tables\Columns\TextColumn::make('brand')
                    ->Label('Marca'),
                Tables\Columns\TextColumn::make('model')
                    ->searchable()
                    ->Label('Modelo'),
                Tables\Columns\TextColumn::make('serial')
                    ->searchable()
                    ->Label('N° Serie'),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->Label('Tipo Equipo'),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->Label('Descripcion'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->Label('Creado')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->Label('Modificado')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
