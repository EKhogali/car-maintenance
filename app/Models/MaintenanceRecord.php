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
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
    // public function serviceTypes()
    // {
    //     return $this->belongsToMany(ServiceType::class);
    // }
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
    return $this->usedParts->sum(fn ($usage) => $usage->quantity * $usage->unit_price);
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
