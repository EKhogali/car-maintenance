<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartResource\Pages;
use App\Filament\Resources\PartResource\RelationManagers;
use App\Models\Part;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartResource extends Resource
{
    protected static ?string $model = Part::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')
            ->label('اسم القطعة')
            ->required()
            ->maxLength(255),

        TextInput::make('code')
            ->label('رمز القطعة')
            ->nullable(),

        TextInput::make('price')
            ->label('سعر القطعة')
            ->numeric()
            ->required(),
    ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')->label('اسم القطعة')->searchable(),
            TextColumn::make('code')->label('الرمز')->toggleable(),
            TextColumn::make('price')->label('السعر')->money('lyd'),
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('price')
                ->label('بها سعر؟')
                ->nullable()
                ->trueLabel('بها سعر')
                ->falseLabel('بلا سعر'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListParts::route('/'),
            'create' => Pages\CreatePart::route('/create'),
            'edit' => Pages\EditPart::route('/{record}/edit'),
        ];
    }
}
