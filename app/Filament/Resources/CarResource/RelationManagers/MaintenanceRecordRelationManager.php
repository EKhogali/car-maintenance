<?php

namespace App\Filament\Resources\CarResource\RelationManagers;

use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Mechanic;
use App\Models\ServiceType;

class MaintenanceRecordRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenanceRecords';

    protected static ?string $title = 'سجلات الصيانة';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('service_date')
                ->label(__('maintenance.service_date'))
                ->default(now())
                ->required(),

            Forms\Components\TextInput::make('odometer_reading')
                ->numeric()
                ->label(__('maintenance.odometer')),

            Forms\Components\Select::make('mechanic_id')
                ->relationship('mechanic', 'name')
                ->searchable()
                ->label(__('maintenance.mechanic')),

            Forms\Components\Select::make('serviceTypes')
                ->multiple()
                ->relationship('serviceTypes', 'name')
                ->preload()
                ->label(__('maintenance.service_types'))
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    // Fetch prices and calculate total
                    $total = ServiceType::whereIn('id', $state)->sum('price');
                    $set('cost', $total);
                }),

            Forms\Components\TextInput::make('cost')
                ->numeric()
                ->prefix('LYD')
                ->label(__('maintenance.cost'))
                ->dehydrated(true),

            Forms\Components\TextInput::make('discount')
                ->label(__('maintenance.discount'))
                ->numeric()
                ->default(0)
                ->prefix('LYD')
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                    $set('due', max(0, ($get('cost') ?? 0) - $state))
                ),

            Forms\Components\TextInput::make('due')
                ->label(__('maintenance.due'))
                ->prefix('LYD')
                ->disabled()
                ->dehydrated(true),

            Forms\Components\DatePicker::make('next_due_date')
                ->label(__('maintenance.next_due_date')),

            Forms\Components\Textarea::make('description')
                ->label(__('maintenance.description'))
                ->rows(3),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('service_date')
                ->label(__('maintenance.service_date'))
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('mechanic.name')
                ->label(__('maintenance.mechanic'))
                ->sortable(),

            Tables\Columns\TextColumn::make('cost')
                ->label(__('maintenance.cost'))
                ->money('lyd')
                ->sortable(),

            Tables\Columns\TextColumn::make('odometer_reading')
                ->label(__('maintenance.odometer')),

            Tables\Columns\TextColumn::make('serviceTypes.name')
                ->label(__('maintenance.service_types'))
                ->badge()
                ->separator(','),
        ])
        ->filters([
            Tables\Filters\Filter::make('due')
                ->label(__('maintenance.filter.due'))
                ->query(fn ($query) => $query->whereDate('next_due_date', '<=', now())),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Html2MediaAction::make('print')
    ->label('طباعة')
    ->content(fn($record) => view('customer-invoice', ['record' => $record]))
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function beforeSave(Form $form, Model $record): void
    {
        $servicesCost = $record->serviceTypes()->sum('price');
        $partsCost = $record->partUsages->sum(fn($item) => $item->quantity * $item->unit_price);
        $record->cost = $servicesCost + $partsCost;

        // Apply discount
        $record->due = max(0, $record->cost - $record->discount);
    }



}

