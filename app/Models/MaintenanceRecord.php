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

protected static function booted(): void
{
    static::saving(function ($record) {
        $servicesTotal = $record->services->sum('price');
        $partsTotal = $record->partUsages->sum(fn($u) => $u->unit_price * $u->quantity);
        $discount = $record->discount ?? 0;

        // Total paid amount
        $totalPaid = $servicesTotal + $partsTotal - $discount;

        // Mechanic Pct fallback from relation
        $mechanicPct = $record->mechanic_pct ?? $record->mechanic?->work_pct ?? 0;
        $record->mechanic_pct = $mechanicPct;

        // Mechanic Amount from services after discount
        $record->mechanic_amount = max(0, $servicesTotal - $discount) * $mechanicPct / 100;

        // Supervisor defaults to 10%
        $record->supervisor_pct = $record->supervisor_pct ?? 10;
        $record->supervisor_amount = $totalPaid * $record->supervisor_pct / 100; 
        $record->company_amount = $totalPaid - $record->mechanic_amount - $record->supervisor_amount - $partsTotal;
    });
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
