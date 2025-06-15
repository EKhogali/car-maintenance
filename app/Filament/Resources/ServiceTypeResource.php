<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceTypeResource\Pages;
use App\Filament\Resources\ServiceTypeResource\RelationManagers;
use App\Models\ServiceType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceTypeResource extends Resource
{
    protected static ?string $model = ServiceType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('service_type.name'))
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(100),

            Forms\Components\Textarea::make('description')
                ->label(__('service_type.description'))
                ->rows(3)
                ->maxLength(500),

            Forms\Components\TextInput::make('price')
                ->label(__('service_type.price'))
                ->numeric()
                ->prefix('LYD')
                ->default(0),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('service_type.name'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('price')
                ->label(__('service_type.price'))
                ->sortable()
    ->formatStateUsing(fn ($state) => number_format($state, 2) . ''),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('service_type.created_at'))
                ->sortable()
                ->since(),
        ])
        ->filters([
            Tables\Filters\Filter::make('high_cost')
                ->label(__('service_type.filter.high_cost'))
                ->query(fn ($query) => $query->where('price', '>', 200)),
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
            'index' => Pages\ListServiceTypes::route('/'),
            'create' => Pages\CreateServiceType::route('/create'),
            'edit' => Pages\EditServiceType::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('service_type.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('Master Data');
    }

    public static function getModelLabel(): string
    {
        return __('service_type.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('service_type.plural_label');
    }
}
