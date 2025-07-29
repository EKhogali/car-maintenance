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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ComponentContainer;



class MaintenanceRecordResource extends Resource
{
    protected static ?string $model = MaintenanceRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        return $form->schema([
            Forms\Components\Section::make(__('car.plural_label'))
                ->schema([
                    Forms\Components\Placeholder::make('record_id')
                        ->label('رقم السجل (للربط مع قطع الغيار)')
                        ->content(fn($record) => $record?->id)
                        ->visible(fn($record) => filled($record?->id))
                        ->columnSpanFull(),


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
                ->label(__('maintenance.mechanic'))
                ->reactive()                                        // ← required for afterStateUpdated
                ->afterStateUpdated(function ($state, $set, $get) {
                    $defaultPct = \App\Models\Mechanic::find($state)?->work_pct ?? 0;

                    // If the current value is still the previous default (i.e. not edited manually),
                    // update it; otherwise leave the manually-overridden value alone.
                    $wasManual = ($get('mechanic_pct') ?? null) !== ($get('mechanic_default_pct') ?? null);

                    if (!$wasManual) {
                        $set('mechanic_pct', $defaultPct);
                    }

                    // remember the new default for next time
                    $set('mechanic_default_pct', $defaultPct);
                }),


            Forms\Components\TextInput::make('mechanic_pct')
                ->label('نسبة الفني (%)')
                ->numeric()
                ->suffix('%')
                ->disabled()          // show-only
                ->dehydrated(true),   // still gets saved with the record

            // (optional) tiny helper field – stays in Livewire only, never saved:
            Forms\Components\Hidden::make('mechanic_default_pct')->dehydrated(false),

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
                ->label(__('maintenance.odometer'))
                ->required()
                ->numeric(),

            Forms\Components\Textarea::make('first_check')
                ->label('الفحص الأولي')
                ->rows(4),

            Forms\Components\Textarea::make('detailed_check')
                ->label('الفحص التفصيلي')
                ->rows(4),



            Forms\Components\Section::make(__('payment.label'))
                ->schema([
                    Forms\Components\TextInput::make('advance_payment')
                        ->label(__('maintenance.advance_payment')) // Add to lang/ar.json
                        ->numeric()
                        ->prefix('د.ل')
                        ->default(0)
                        ->reactive()
                        ->afterStateUpdated(
                            fn($state, callable $set, callable $get) =>
                            $set('due', max(0, ($get('cost') ?? 0) - ($get('discount') ?? 0) - $state))
                        ),

                    Forms\Components\TextInput::make('advance_payment_note')
                        ->label(__('maintenance.advance_payment_note'))
                        ->maxLength(255)
                        ->placeholder('أدخل ملاحظة عن الدفعة مثل طريقة الدفع أو رقم الإيصال'),
                ])
                // ->aside()
                // ->icon('heroicon-o-banknotes')
                ->columns(1),


            Forms\Components\Section::make(__('حسابات الصيانة'))
                ->schema([




                    Forms\Components\Repeater::make('services')
                        ->label('الخدمات')
                        ->relationship('services')
                        ->schema([
                            Select::make('service_type_id')
                                ->label('الخدمة')
                                ->options(function () {
                                    return \App\Models\ServiceCategory::with('serviceTypes')->get()
                                        ->flatMap(function ($category) {
                                            return $category->serviceTypes->mapWithKeys(function ($type) use ($category) {
                                                return [$type->id => $category->name . ' - ' . $type->name];
                                            });
                                        });
                                })
                                ->getOptionLabelFromRecordUsing(function ($record) {
                                    return $record->serviceCategory?->name . ' - ' . $record->name;
                                })
                                ->relationship('serviceType', 'name')
                                ->preload()
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $existingPrice = $get('price');

                                    $shouldUpdate = is_null($existingPrice) || $existingPrice === '' || floatval($existingPrice) === 0.0;

                                    if ($shouldUpdate && filled($state)) {
                                        $serviceType = \App\Models\ServiceType::query()
                                            ->select('price')
                                            ->find($state);

                                        if ($serviceType && $serviceType->price > 0) {
                                            $set('price', $serviceType->price);
                                        }
                                    }
                                })
                                ->afterStateHydrated(function (callable $get, callable $set) {
                                    $price = $get('price');
                                    $serviceTypeId = $get('service_type_id');

                                    if ((is_null($price) || $price === '' || floatval($price) === 0.0) && filled($serviceTypeId)) {
                                        $serviceType = \App\Models\ServiceType::select('price')->find($serviceTypeId);

                                        if ($serviceType && $serviceType->price > 0) {
                                            $set('price', $serviceType->price);
                                        }
                                    }
                                })

                            ,

                            TextInput::make('price')
                                ->label('السعر')
                                ->numeric()
                                ->reactive()
                                ->required(),
                        ])
                        ->columns(2)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $total = collect($state)->sum('price');
                            $set('cost', $total);
                            $set('due', max(
                                0,
                                $total - ($get('discount') ?? 0) - ($get('advance_payment') ?? 0)
                            ));
                        }),
                    Forms\Components\TextInput::make('cost')
                        ->label(__('maintenance.cost'))
                        ->numeric()
                        ->prefix('د.ل')
                        ->disabled()
                        ->reactive()
                        ->dehydrated(true),

                    Forms\Components\TextInput::make('discount')
                        ->label(__('maintenance.discount'))
                        ->numeric()
                        ->default(0)
                        ->prefix('د.ل.')
                        ->reactive()
                        ->afterStateUpdated(
                            fn($state, callable $set, callable $get) =>
                            $set('due', max(0, ($get('cost') ?? 0) - $state - ($get('advance_payment') ?? 0)))
                        ),

                    Forms\Components\TextInput::make('due')
                        ->label(__('maintenance.due'))
                        ->numeric()
                        ->prefix('د.ل.')
                        ->disabled()
                        ->reactive()
                        ->dehydrated(true)
                        ->afterStateHydrated(function (callable $set, $state, callable $get) {
                            $set('due', max(0, ($get('cost') ?? 0) - ($get('discount') ?? 0) - ($get('advance_payment') ?? 0)));
                        }),

                ])
                ->columns(1),



            Forms\Components\DatePicker::make('next_due_date')
                ->label(__('maintenance.next_due_date'))
                ->default(now()->addMonth()),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label(__('maintenance.id'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('car.license_plate')
                ->label(__('maintenance.car')),

            Tables\Columns\TextColumn::make('mechanic.name')
                ->label(__('maintenance.mechanic'))
                ->toggleable(),

            Tables\Columns\TextColumn::make('service_date')
                ->label(__('maintenance.service_date'))
                ->date()
                ->toggleable(),

            Tables\Columns\TextColumn::make('payment_method')
                ->label(__('maintenance.payment_method'))
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->formatStateUsing(fn(string $state) => match ($state) {
                    '0' => 'نقدي',
                    '1' => 'بطاقة',
                    '2' => 'تحويل',
                    default => $state,
                }),


            Tables\Columns\TextColumn::make('cost')
                ->label('التكلفة'),

            Tables\Columns\TextColumn::make('discount')
                ->label('الخصم')
                ->formatStateUsing(fn($state) => number_format($state, 2) . ' د.ل')
                ->toggleable(),

            Tables\Columns\TextColumn::make('due')
                ->label('المبلغ المستحق'),

            Tables\Columns\TextColumn::make('advance_payment')
                ->label('الدفعة المقدمة')
                ->formatStateUsing(fn($state) => number_format($state, 2) . ' د.ل')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('remained')
                ->label('المتبقي')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('mechanic_pct')
                ->label('نسبة الفني')
                ->formatStateUsing(fn($state) => $state ? $state . '%' : '--')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('mechanic_amount')
                ->label('مستحق الفني')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('supervisor_amount')
                ->label('مستحق المشرف')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('company_amount')
                ->label('نصيب الشركة')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('odometer_reading')
                ->label(__('maintenance.odometer'))
        ])
            ->filters([
                Tables\Filters\Filter::make('due')
                    ->label(__('maintenance.filter.due'))
                    ->query(fn($query) => $query->whereDate('next_due_date', '<=', now())),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Tables\Actions\Action::make('manage_parts')
                //     ->label('قطع الغيار')
                //     ->icon('heroicon-o-wrench')
                //     ->url(
                //         fn($record) =>
                //         \App\Filament\Resources\MaintenanceRecordPartResource::getUrl('index', [
                //             'ownerRecord' => $record->id,   // pass current maintenance record
                //         ])
                //     )
                //     ->openUrlInNewTab(),   // optional: open in a new tab


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
                            ->default(fn($record) => $record->mechanic_pct), // ✅ Pre-fill current value
                    ])
                    ->action(function (MaintenanceRecord $record, array $data) {
                        $record->mechanic_pct = $data['mechanic_pct'];
                        $record->save();
                    })
                    ->modalHeading('نسبة الفني')
                    ->requiresConfirmation()
                    ->visible(fn() => in_array(auth()->id(), [1, 2, 3])),


                Html2MediaAction::make('print_receive_form')
                    ->label('نموذج استلام سيارة')
                    // ->modal(false)
                    ->icon('heroicon-o-printer')
                    ->preview() // ✅ This works now
                    // ->content(fn ($record) => view('car-receive-form', ['car' => $record])),
                    ->content(fn($record) => view('car-receive-form', ['car' => $record->car])),



                Html2MediaExportAction::make('print')
                    ->label('فاتورة الزبون')
                    // ->modal(false)
                    ->preview()
                    ->content(fn($record) => view('customer-invoice', ['record' => $record])),

                Html2MediaExportAction::make('print_mechanic_invoice')
                    ->label('فاتورة الفني')
                    ->icon('heroicon-o-document-text')
                    ->preview()                                // lets the user see before printing
                    ->visible(fn($record) => filled($record->mechanic_id))
                    ->content(fn($record) => view('mechanic-invoice', [
                        'record' => $record,
                        'mechanicPct' => $record->mechanic_pct
                            ?: ($record->mechanic?->work_pct ?? 0), // fallback to mechanic default
                    ])),


                Html2MediaExportAction::make('print_internal_financial_summary')
                    ->label('تقرير مالي ')
                    // ->modal(false)
                    ->preview()
                    ->content(fn($record) => view('internal-financial-summary', ['record' => $record])),


            ])
            ->defaultSort('id', 'desc');
        // ->defaultSort('service_date', 'desc');
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
        // $servicesCost = $record->serviceTypes()->sum('price');

        // // Calculate total part usage cost
        // $partsCost = $record->partUsages->sum(function ($part) {
        //     return $part->quantity * $part->unit_price;
        // });

        // // Combine
        // $record->cost = $servicesCost + $partsCost;

        // // Apply discount if set
        // $record->due = max(0, $record->cost - ($record->discount ?? 0));

        $record->recalculateTotals();

    }


    public static function beforeCreate(Form $form, Model $record): void
    {
        if ($record->mechanic_id) {
            $record->mechanic_pct = \App\Models\Mechanic::find($record->mechanic_id)?->work_pct ?? 0;
        }
    }


    // MaintenanceRecord.php


    public static function getNavigationBadge(): ?string
    {
        return (string) MaintenanceRecord::dueSoon()->count();
    }

}
