<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceRecordResource\RelationManagers\PartUsagesRelationManager;

use App\Filament\Resources\MaintenanceRecordResource\Pages;
use App\Filament\Resources\MaintenanceRecordResource\RelationManagers;
use App\Models\MaintenanceRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Torgodly\Html2Media\Actions\Html2MediaAction;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction as Html2MediaExportAction;

class MaintenanceRecordResource extends Resource
{
    protected static ?string $model = MaintenanceRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Select::make('car_id')
            ->relationship('car', 'license_plate')
            ->searchable()
            ->required()
            ->label(__('maintenance.car')),

        Forms\Components\Select::make('mechanic_id')
            ->relationship('mechanic', 'name')
            ->searchable()
            ->label(__('maintenance.mechanic')),

        Forms\Components\DatePicker::make('service_date')
            ->required()
                ->default(now())
            ->label(__('maintenance.service_date')),

        Forms\Components\TextInput::make('odometer_reading')
            ->numeric()
            ->label(__('maintenance.odometer')),
            
            Forms\Components\TextInput::make('first_check')
                ->label('الفحص الأولي')
                ->maxLength(255),

            Forms\Components\Textarea::make('detailed_check')
                ->label('الفحص التفصيلي')
                ->rows(4),

        Forms\Components\Textarea::make('description')
            ->label(__('maintenance.description')),

        Forms\Components\TextInput::make('cost')
            ->numeric()
            ->prefix('LYD')
            ->label(__('maintenance.cost')),

        Forms\Components\DatePicker::make('next_due_date')
            ->label(__('maintenance.next_due_date')),

        Forms\Components\Select::make('serviceTypes')
            ->multiple()
            ->relationship('serviceTypes', 'name')
            ->searchable()
            ->label(__('maintenance.service_types')),
    ]);
}

    public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('car.license_plate')
            ->label(__('maintenance.car')),

        Tables\Columns\TextColumn::make('mechanic.name')
            ->label(__('maintenance.mechanic')),

        Tables\Columns\TextColumn::make('service_date')
            ->label(__('maintenance.service_date'))
            ->date(),

        Tables\Columns\TextColumn::make('cost')
            ->label(__('maintenance.cost'))
            ->money('lyd'),

        Tables\Columns\TextColumn::make('odometer_reading')
            ->label(__('maintenance.odometer')),
    ])
    ->filters([
        Tables\Filters\Filter::make('due')
            ->label(__('maintenance.filter.due'))
            ->query(fn ($query) => $query->whereDate('next_due_date', '<=', now())),
    ])
    ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
        
        Tables\Actions\Action::make('view_check')
            ->label('عرض الفحص')
            ->icon('heroicon-o-eye')
            ->modalHeading('تفاصيل الفحص')
            ->modalSubheading(fn ($record) => 'للسيارة: ' . $record->car->make . ' - ' . $record->car->model)
            ->modalContent(fn ($record) => view('components.modals.maintenance-check', compact('record')))
            ->modalSubmitAction(false),

        Html2MediaExportAction::make('print')
        ->label('طباعة')
        ->preview()
        ->content(fn ($record) => view('customer-invoice', ['record' => $record])),

        // Html2MediaAction::make('print')
        //     ->content(fn($record) => view('customer-invoice', ['record' => $record])),

// Html2MediaExportAction::make('print')
//             ->label('طباعة PDF')
//             ->view('customer-invoice')
//             ->filename(fn ($record) => 'invoice-' . $record->id),

        // Tables\Actions\DeleteBulkAction::make(),
    ])
    ->defaultSort('service_date', 'desc');
}

    public static function getRelations(): array
{
    return [
        PartUsagesRelationManager::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceRecords::route('/'),
            'create' => Pages\CreateMaintenanceRecord::route('/create'),
            'edit' => Pages\EditMaintenanceRecord::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationLabel(): string
    {
        return __('maintenance.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('Transactional Data');
    }

    public static function getModelLabel(): string
    {
        return __('maintenance.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('maintenance.plural_label');
    }

    public static function beforeSave(Form $form, Model $record): void
{
    // Calculate total service type cost
    $servicesCost = $record->serviceTypes()->sum('price');

    // Calculate total part usage cost
    $partsCost = $record->partUsages->sum(function ($part) {
        return $part->quantity * $part->unit_price;
    });

    // Combine
    $record->cost = $servicesCost + $partsCost;

    // Apply discount if set
    $record->due = max(0, $record->cost - ($record->discount ?? 0));
}

}
