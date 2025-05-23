<?php

namespace App\Filament\Resources\MaintenanceRecordResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
    use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PartUsagesRelationManager extends RelationManager
{
    protected static string $relationship = 'partUsages';



public function form(Form $form): Form
{
    return $form->schema([
        Select::make('part_id')
            ->label('القطعة')
            ->relationship('part', 'name')
            ->required(),

        TextInput::make('quantity')
            ->label('الكمية')
            ->numeric()
            ->default(1)
            ->required(),

        TextInput::make('unit_price')
            ->label('السعر للوحدة')
            ->numeric()
            ->required(),
    ]);
}



public function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('part.name')->label('القطعة'),
            TextColumn::make('quantity')->label('الكمية'),
            TextColumn::make('unit_price')->label('سعر الوحدة')->money('LYD'),
            TextColumn::make('total')->label('الإجمالي')->formatStateUsing(
                fn ($record) => number_format($record->quantity * $record->unit_price, 2) . ' LYD'
            ),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}
}
