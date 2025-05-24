<?php
namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages;
use Filament\Tables\Actions\Action;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationLabel(): string
    {
        return __('users.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('users.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('users.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('users.name'))
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label(__('users.email'))
                ->email()
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('password')
                ->label(__('users.password'))
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->required(fn (string $context) => $context === 'create')
                ->visible(fn (string $context) => $context === 'create'),

            Forms\Components\Select::make('roles')
                ->label(__('users.roles'))
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('users.name'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label(__('users.email'))->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('users.created_at'))->dateTime('Y-m-d'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Action::make('change_password')
                    ->label(__('users.change_password'))
                    ->icon('heroicon-o-lock-closed')
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->label(__('users.new_password'))
                            ->password()
                            ->required()
                            ->minLength(6),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'password' => Hash::make($data['new_password']),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('users.change_password_modal_title')),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->id() !== 1, fn ($query) => $query->where('id', '!=', 1));
    }
}


