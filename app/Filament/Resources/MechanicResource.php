<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MechanicResource\Pages;
use App\Filament\Resources\MechanicResource\RelationManagers;
use App\Models\Mechanic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MechanicResource extends Resource
{
    protected static ?string $model = Mechanic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('name')
            ->label(__('mechanic.name'))
            ->required(),

        Forms\Components\TextInput::make('phone')
            ->label(__('mechanic.phone')),

        Forms\Components\TextInput::make('email')
            ->label(__('mechanic.email'))
            ->email()
            ->nullable(),

        Forms\Components\TextInput::make('specialty')
            ->label(__('mechanic.specialty')),

        Forms\Components\TextInput::make('work_pct')
            ->label(__('mechanic.work_pct'))
            ->numeric()
            ->minValue(0)
            ->suffix('%')
            ->default(0)
            ->required(),

        Forms\Components\DatePicker::make('hire_date')
            ->label(__('mechanic.hire_date')),

        Forms\Components\Toggle::make('is_active')
            ->label(__('mechanic.active'))
            ->default(true),

        Forms\Components\Textarea::make('notes')
            ->label(__('mechanic.notes'))
            ->rows(3),
    ]);
}

    public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('name')
            ->label(__('mechanic.name'))
            ->searchable()
            ->sortable(),

        Tables\Columns\TextColumn::make('phone')
            ->label(__('mechanic.phone')),

        Tables\Columns\TextColumn::make('specialty')
            ->label(__('mechanic.specialty'))
            ->sortable(),
            
         Tables\Columns\TextColumn::make('work_pct')
            ->label(__('mechanic.work_pct'))
            ->sortable()
            ->suffix('%'),
            

        Tables\Columns\IconColumn::make('is_active')
            ->boolean()
            ->label(__('mechanic.active')),

        Tables\Columns\TextColumn::make('hire_date')
            ->label(__('mechanic.hire_date'))
            ->date(),
    ])
    ->filters([
        Tables\Filters\TernaryFilter::make('is_active')
            ->label(__('mechanic.filter.active_status')),
    ])
    ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
    ])
    ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
    ])
    ->defaultSort('name');
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
            'index' => Pages\ListMechanics::route('/'),
            'create' => Pages\CreateMechanic::route('/create'),
            'edit' => Pages\EditMechanic::route('/{record}/edit'),
        ];
    }
}
