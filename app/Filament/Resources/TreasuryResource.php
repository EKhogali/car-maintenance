<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreasuryResource\Pages;
use App\Filament\Resources\TreasuryResource\RelationManagers;
use App\Models\Treasury;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreasuryResource extends Resource
{
    protected static ?string $model = Treasury::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('transaction_date')
                ->label('تاريخ المعاملة')
                ->default(now())
                ->required(),
            Forms\Components\Select::make('account_id')
                ->label(__('treasury.account'))
                ->relationship('account', 'name') // assumes Treasury belongsTo Account
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('amount')->label(__('treasury.amount'))->numeric(),
            Forms\Components\Select::make('type')
                ->label(__('treasury.type'))
                ->options([
                    'income' => __('treasury.income'),
                    'expense' => __('treasury.expense'),
                ]),
            Forms\Components\TextInput::make('reference')->label(__('treasury.reference')),
            Forms\Components\Textarea::make('note')->label(__('treasury.note')),
            Forms\Components\TagsInput::make('tags')
                ->label(__('treasury.tags'))
                ->suggestions(\App\Models\Tag::pluck('name')->toArray()),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('transaction_date')
                ->label('تاريخ المعاملة')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('account.name')->label(__('treasury.account')),
            Tables\Columns\TextColumn::make('amount')->label(__('treasury.amount')),
            Tables\Columns\TextColumn::make('type')->label(__('treasury.type'))->formatStateUsing(fn($state) => __('treasury.' . $state)),
            Tables\Columns\TextColumn::make('reference')->label(__('treasury.reference')),
            Tables\Columns\TextColumn::make('note')->label(__('treasury.note')),
            Tables\Columns\TagsColumn::make('tags.name')->label(__('treasury.tags')),
            Tables\Columns\TextColumn::make('created_at')->label(__('treasury.created_at'))->date(),

        ])->filters([
                    Tables\Filters\Filter::make('date_range')
                        ->form([
                            Forms\Components\DatePicker::make('from')->label('من تاريخ'),
                            Forms\Components\DatePicker::make('to')->label('إلى تاريخ'),
                        ])
                        ->query(function (Builder $query, array $data) {
                            return $query
                                ->when($data['from'], fn($q) => $q->whereDate('transaction_date', '>=', $data['from']))
                                ->when($data['to'], fn($q) => $q->whereDate('transaction_date', '<=', $data['to']));
                        }),

                    Tables\Filters\SelectFilter::make('type')
                        ->label('النوع')
                        ->options([
                            'income' => 'إيراد',
                            'expense' => 'مصروف',
                        ])
                        // ->query(function (Builder $query, $state) {
                        //     $query->when($state, fn($q) => $q->where('type', $state));
                        // })
                                                ,

                    Tables\Filters\Filter::make('tags')
                        ->form([
                            Forms\Components\Select::make('tags')
                                ->label('الوسوم')
                                ->options(\App\Models\Tag::pluck('name', 'id')->toArray())
                                ->multiple()
                                ->preload()
                        ])
                        ->query(function (Builder $query, array $data) {
                            if (isset($data['tags']) && count($data['tags'])) {
                                $query->whereHas('tags', fn($q) => $q->whereIn('tags.id', $data['tags']));
                            }
                        }),
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
            'index' => Pages\ListTreasuries::route('/'),
            'create' => Pages\CreateTreasury::route('/create'),
            'edit' => Pages\EditTreasury::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string
    {
        return __('Treasury Management');
    }

    public static function getNavigationLabel(): string
    {
        return __('treasury.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('treasury.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('treasury.plural_label');
    }

}
