<?php

namespace App\Observers;

class MaintenanceRecordServiceObserver
{
    public function saved($service)
{
    $service->maintenanceRecord->recalculateTotals();
}

public function deleted($service)
{
    $service->maintenanceRecord->recalculateTotals();
}

}
