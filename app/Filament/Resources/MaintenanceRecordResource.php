<?php

namespace App\Filament\Resources;


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
// use Torgodly\Html2Media\Actions\Html2MediaAction;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction as Html2MediaExportAction;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;

class MaintenanceRecordResource extends Resource
{
    protected static ?string $model = MaintenanceRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    
    return $form->schema([
        Forms\Components\Section::make(__('car.plural_label'))
        ->schema([
        Forms\Components\Select::make('car_id')
            ->label(__('maintenance.car'))
            ->relationship('car', 'license_plate')
            ->searchable()
            ->preload()
            ->createOptionForm([
                Forms\Components\Select::make('customer_id')
                    ->label(__('car.customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label(__('customer.name'))
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('phone')
                            ->label(__('customer.phone'))
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('email')
                            ->label(__('customer.email'))
                            ->email()
                            ->maxLength(100),

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
                    ]),

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
                    ->required(),

                Forms\Components\TextInput::make('license_plate')
                    ->label(__('car.license_plate'))
                    ->required(),

                Forms\Components\TextInput::make('color')
                    ->label(__('car.color')),

                Forms\Components\TextInput::make('mileage')
                    ->label(__('car.mileage'))
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('engine_type')
                    ->label(__('car.engine_type')),

                Forms\Components\TextInput::make('transmission')
                    ->label(__('car.transmission')),

                Forms\Components\Textarea::make('notes')
                    ->label(__('car.notes'))
                    ->rows(3),
            ])
            ->required()
            ->preload(),
            ])
            ->columns(1),
        // ----------------------

        Forms\Components\Select::make('mechanic_id')
            ->relationship('mechanic', 'name')
            ->searchable()
            ->preload()
            ->label(__('maintenance.mechanic')),

        Forms\Components\DatePicker::make('service_date')
            ->required()
            ->default(now())
            ->label(__('maintenance.service_date')),

        Forms\Components\Select::make('payment_method')
            ->required()
            ->options([
                '0' => 'كاش',
                '1' => 'بطاقة',
                '2' => 'تحويل',
            ])
            ->default('0')
            ->label(__('maintenance.payment_method'))
            ->native(false),


        Forms\Components\TextInput::make('odometer_reading')
            ->numeric()
            ->label(__('maintenance.odometer')),
            
            Forms\Components\Textarea::make('first_check')
                ->label('الفحص الأولي')
                ->rows(4),

            Forms\Components\Textarea::make('detailed_check')
                ->label('الفحص التفصيلي')
                ->rows(4),

                Forms\Components\Select::make('serviceTypes')
                    ->multiple()
                    ->relationship('serviceTypes', 'name')
                    ->preload()
                    ->label(__('maintenance.service_types'))
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $total = \App\Models\ServiceType::whereIn('id', $state)->sum('price');
                        $set('cost', $total);
                        $set('due', max(0, $total - ($get('discount') ?? 0)));
                    }),
        // Forms\Components\Textarea::make('description')
        //     ->label(__('maintenance.description')),
            Forms\Components\TextInput::make('cost')
                ->label(__('maintenance.cost'))
                ->numeric()
                ->prefix('د.ل')
                ->disabled()
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
                    ->numeric()
                    ->prefix('د.ل.')
                    ->disabled()
                    ->dehydrated(true),




        Forms\Components\DatePicker::make('next_due_date')
            ->label(__('maintenance.next_due_date'))
            ->default(now()->addMonth()),

    ]);
}

    public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('id')
            ->label(__('maintenance.id')),
        Tables\Columns\TextColumn::make('car.license_plate')
            ->label(__('maintenance.car')),

        Tables\Columns\TextColumn::make('mechanic.name')
            ->label(__('maintenance.mechanic')),

        Tables\Columns\TextColumn::make('service_date')
            ->label(__('maintenance.service_date'))
            ->date(),

            Tables\Columns\TextColumn::make('payment_method')
                ->label(__('maintenance.payment_method'))
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn (string $state) => match ($state) {
                    '0' => 'نقدي',
                    '1' => 'بطاقة',
                    '2' => 'تحويل',
                    default => $state,
                }),

        Tables\Columns\TextColumn::make('cost')
            ->label(__('maintenance.cost'))
    ->formatStateUsing(fn ($state) => number_format($state, 2) . ''),

        Tables\Columns\TextColumn::make('odometer_reading')
            ->label(__('maintenance.odometer')),
    ])
    ->filters([
        Tables\Filters\Filter::make('due')
            ->label(__('maintenance.filter.due'))
            ->query(fn ($query) => $query->whereDate('next_due_date', '<=', now())),
    ])
    ->actions([
        // Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
        
            Tables\Actions\Action::make('edit_mechanic_pct')
                ->label('نسبة الفني')
                ->icon('heroicon-o-pencil')
                ->form([
                    Forms\Components\TextInput::make('mechanic_pct')
                        ->label('نسبة الفني (%)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->required()
                        ->default(fn ($record) => $record->mechanic_pct), // ✅ Pre-fill current value
                ])
                ->action(function (MaintenanceRecord $record, array $data) {
                    $record->mechanic_pct = $data['mechanic_pct'];
                    $record->save();
                })
                ->modalHeading('نسبة الفني')
                ->requiresConfirmation()
                ->visible(fn () => in_array(auth()->id(), [1, 2, 3])),


        Html2MediaAction::make('print_receive_form')
            ->label('نموذج استلام سيارة')
            // ->modal(false)
            ->icon('heroicon-o-printer')
            ->preview() // ✅ This works now
            // ->content(fn ($record) => view('car-receive-form', ['car' => $record])),
            ->content(fn ($record) => view('car-receive-form', ['car' => $record->car])),



        
Html2MediaExportAction::make('print_internal_financial_summary')
    ->label('تقرير مالي ')
    // ->modal(false)
    ->preview()
    ->content(fn ($record) => view('internal-financial-summary', ['record' => $record])),


        Html2MediaExportAction::make('print')
        ->label('فاتورة الزبون')        
            // ->modal(false)
        ->preview()
        ->content(fn ($record) => view('customer-invoice', ['record' => $record])),


    ])
    ->defaultSort('service_date', 'desc');
}

public static function getRelations(): array
{
    return [
        // UsedPartsRelationManager::class,
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


public static function beforeCreate(Form $form, Model $record): void
{
    if ($record->mechanic_id) {
        $record->mechanic_pct = \App\Models\Mechanic::find($record->mechanic_id)?->work_pct ?? 0;
    }
}


}
