<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceType extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceTypeFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description', 'price'];
    // public function maintenanceRecords()
    // {
    //     return $this->belongsToMany(Maintenance_record::class);
    // }

    public function maintenanceRecords()
{
    return $this->belongsToMany(MaintenanceRecord::class)
                ->withPivot('price')
                ;
}

// In Service.php
public function category(): BelongsTo
{
    return $this->belongsTo(ServiceCategory::class, 'service_category_id');
}



}
