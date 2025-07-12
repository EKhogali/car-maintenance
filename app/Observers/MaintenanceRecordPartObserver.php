<?php

namespace App\Observers;

class MaintenanceRecordPartObserver
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
