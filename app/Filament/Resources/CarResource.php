<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CarResource\RelationManagers\MaintenanceRecordRelationManager;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;
use Illuminate\Validation\Rule;


class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Select::make('customer_id')
            ->label(__('car.customer'))
            ->relationship('customer', 'name')
            ->searchable()
            ->required(),

        Forms\Components\TextInput::make('make')
            ->label(__('car.make'))
            ->required(),

        Forms\Components\TextInput::make('model')
            ->label(__('car.model'))
            ->required(),

        Forms\Components\TextInput::make('year')
            ->label(__('car.year'))
            ->numeric()
            ->required(),

        Forms\Components\TextInput::make('vin')
            ->label(__('car.vin'))
            ->required()
            ->rule(function ($context) {
                return $context === 'create'
                    ? [Rule::unique('cars', 'vin')]
                    : []; // No uniqueness rule on edit
            }),

        Forms\Components\TextInput::make('license_plate')
            ->label(__('car.license_plate'))
            ->required(),

        Forms\Components\TextInput::make('color')
            ->label(__('car.color')),

        Forms\Components\TextInput::make('mileage')
            ->label(__('car.mileage'))
            ->numeric()
            ->required(),

        Forms\Components\TextInput::make('engine_type')
            ->label(__('car.engine_type')),

        Forms\Components\TextInput::make('transmission')
            ->label(__('car.transmission')),

        Forms\Components\Textarea::make('notes')
            ->label(__('car.notes'))
            ->rows(3),

            Forms\Components\FileUpload::make('images')
                ->label('صور السيارة عند الاستلام')
                ->disk('public')
                ->directory('car-receive-images')
                ->multiple()
                ->preserveFilenames()
                ->maxFiles(3)
                ->image()
                ->reorderable()
                ->afterStateHydrated(function ($component, $record) {
                    if ($record) {
                        $component->state($record->images->pluck('image_path')->toArray());
                    }
                })
                ->afterStateUpdated(function ($state, $set, $get, $record) {
                    if ($record) {
                        $record->images()->delete();

                        foreach ($state as $file) {
                            $storedPath = is_string($file)
                                ? $file // already stored path
                                : $file->store('car-receive-images', 'public'); // store file

                            $record->images()->create(['image_path' => $storedPath]);
                        }
                    }
                })
    ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('customer.name')
                ->label(__('car.customer'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('make')
                ->label(__('car.make'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('model')
                ->label(__('car.model'))
                ->sortable(),

            Tables\Columns\TextColumn::make('year')
                ->label(__('car.year'))
                ->sortable(),

            Tables\Columns\TextColumn::make('license_plate')
                ->label(__('car.license_plate'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('mileage')
                ->label(__('car.mileage'))
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('car.created_at'))
                ->dateTime()
                ->since(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('make')
                ->label(__('car.filter.make'))
                ->options(Car::query()->pluck('make', 'make')->toArray()),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        Html2MediaAction::make('print_receive_form')
            ->label('طباعة استمارة الاستلام')
    ->modal(false)
            ->icon('heroicon-o-printer')
            ->preview() // ✅ This works now
            ->content(fn ($record) => view('car-receive-form', ['car' => $record])),
            
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])
        ->defaultSort('created_at', 'desc');
}

    public static function getRelations(): array
    {
    return [
        // MaintenanceRecordRelationManager::class,
    ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }

    
    public static function getNavigationLabel(): string
    {
        return __('car.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('Master Data');
    }

    public static function getModelLabel(): string
    {
        return __('car.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('car.plural_label');
    }
}
