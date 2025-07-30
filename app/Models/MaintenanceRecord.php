<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;
use App\Models\Mechanic;
use App\Models\ServiceType;
use App\Models\MaintenanceRecordPart;


class MaintenanceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceRecordFactory> */
    use HasFactory;
    protected $fillable = [
        'car_id',
        'mechanic_id',
        'service_date',
        'odometer_reading',
        'description',
        'cost',
        'discount',
        'due',
        'status',
        'next_service_date',
        'mileage_at_service',
        'mechanic_pct',
        'advance_payment',
        'advance_payment_note',
        'mechanic_amount',
        'supervisor_pct',
        'supervisor_amount',
        'company_amount',
    ];

    public function recalculateTotals(): void
    {
        $servicesTotal = $this->services->sum('price');
        $partsTotal = $this->partUsages->sum(fn($p) => $p->quantity * $p->unit_price);

        $billablePartsTotal = $this->partUsages
            ->filter(fn($p) => $p->unit_price > 0)
            ->sum(fn($p) => $p->unit_price) ?? 0;

        $discount = $this->discount ?? 0;
        $advance = $this->advance_payment ?? 0;
        $mechanicPct = $this->mechanic_pct ?? 0;

        $discountedServiceTotal = max(0, $servicesTotal - $discount);

        $mechanicAmount = round($discountedServiceTotal * $mechanicPct / 100, 2);
        $supervisorAmount = round(($servicesTotal + $billablePartsTotal) * 0.10, 2);
        // $supervisorAmount = round(($servicesTotal + $partsTotal) * 0.10, 2);
        $due = $discountedServiceTotal + $partsTotal;
        $companyAmount = round($due - $advance - $partsTotal - $mechanicAmount - $supervisorAmount, 2);

        $this->services_total = $servicesTotal;
        $this->parts_total = $partsTotal;
        $this->mechanic_amount = $mechanicAmount;
        $this->supervisor_amount = $supervisorAmount;
        $this->company_amount = $companyAmount;
        $this->due = $due;

        $this->saveQuietly(); // Prevent infinite loop if called from observer
    }



    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function partUsages()
    {
        return $this->hasMany(MaintenanceRecordPart::class);
    }

    public function usedParts()
    {
        return $this->hasMany(MaintenanceRecordPart::class);
    }

    public function getPartsTotalAttribute()
    {
        return $this->usedParts->sum(fn($usage) => $usage->quantity * $usage->unit_price);
    }

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class)
            ->withPivot('price')
        ;
    }

    public function services()
    {
        return $this->hasMany(MaintenanceRecordServiceType::class);
    }

    public function scopeDueSoon($query)
    {
        return $query->whereDate('next_due_date', '<=', now()->addDays(3));
    }

}
