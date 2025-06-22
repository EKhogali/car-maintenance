<?php

namespace App\Filament\Resources\MaintenanceRecordPartResource\Pages;

use App\Filament\Resources\MaintenanceRecordPartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceRecordParts extends ListRecords
{
    protected static string $resource = MaintenanceRecordPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
