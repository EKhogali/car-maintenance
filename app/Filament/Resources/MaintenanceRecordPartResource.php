<?php
namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceRecordPartResource\Pages;
use App\Models\MaintenanceRecordPart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MaintenanceRecord;
use App\Models\Part;

class MaintenanceRecordPartResource extends Resource
{
    protected static ?string $model = MaintenanceRecordPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Select::make('maintenance_record_id')
                ->label('طلب الصيانة')
                ->relationship('maintenanceRecord', 'id')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('part_id')
                ->label('القطعة')
                ->relationship('part', 'name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')->label('اسم القطعة')->required(),
                    Forms\Components\TextInput::make('code')->label('رمز القطعة'),
                    Forms\Components\TextInput::make('price')->label('السعر')->numeric()->required(),
                ])
                ->required(),

            Forms\Components\TextInput::make('quantity')
                ->label('الكمية')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('unit_price')
                ->label('سعر الوحدة')
                ->numeric()
                ->required(),

        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('maintenanceRecord.id')
                    ->label('طلب الصيانة')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('part.name')
                    ->label('القطعة')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('الكمية'),

                Tables\Columns\TextColumn::make('unit_price')
                    ->label('سعر الوحدة')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . ' د.ل'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('maintenance_record_id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceRecordParts::route('/'),
            'create' => Pages\CreateMaintenanceRecordPart::route('/create'),
            'edit' => Pages\EditMaintenanceRecordPart::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'قطع الغيار المستخدمة';
    }

    public static function getNavigationGroup(): string
    {
        return __('Transactional Data');
    }

    public static function getModelLabel(): string
    {
        return 'استخدام قطعة';
    }

    public static function getPluralModelLabel(): string
    {
        return 'قطع مستخدمة';
    }
}
