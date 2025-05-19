<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers\CarRelationManager;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('customer.name'))
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('email')
                ->label(__('customer.email'))
                ->email()
                ->maxLength(100),

            Forms\Components\TextInput::make('phone')
                ->label(__('customer.phone'))
                ->required()
                ->maxLength(20),

            Forms\Components\TextInput::make('city')
                ->label(__('customer.city'))
                ->required(),

            Forms\Components\TextInput::make('address')
                ->label(__('customer.address')),

            Forms\Components\TextInput::make('national_id')
                ->label(__('customer.national_id')),

            Forms\Components\Textarea::make('notes')
                ->label(__('customer.notes'))
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('customer.name'))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('phone')
                ->label(__('customer.phone')),

            Tables\Columns\TextColumn::make('city')
                ->label(__('customer.city')),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('customer.created_at'))
                ->since(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('city')
                ->label(__('customer.filter.city'))
                ->options(
                    Customer::query()->pluck('city', 'city')->unique()->toArray()
                ),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])
        ->defaultSort('created_at', 'desc');
}

    public static function getRelations(): array
    {
        return [
        CarRelationManager::class,
    ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
