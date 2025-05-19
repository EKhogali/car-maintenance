<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarRelationManager extends RelationManager
{
    protected static string $relationship = 'cars';

    public function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('make')->required()->label(__('car.make')),
        Forms\Components\TextInput::make('model')->required()->label(__('car.model')),
        Forms\Components\TextInput::make('year')->numeric()->required()->label(__('car.year')),
        Forms\Components\TextInput::make('license_plate')->required()->label(__('car.license_plate')),
    ]);
}

public function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('make')->label(__('car.make')),
        Tables\Columns\TextColumn::make('model')->label(__('car.model')),
        Tables\Columns\TextColumn::make('year')->label(__('car.year')),
        Tables\Columns\TextColumn::make('license_plate')->label(__('car.license_plate')),
    ]);
}
}
