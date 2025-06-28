<?php

// app/Models/MaintenanceRecordServiceType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRecordServiceType extends Model
{
    protected $table = 'maintenance_record_service_type';

    protected $fillable = [
        'service_type_id',
        'price',
    ];

    public $timestamps = false; // ⬅️ Important: no timestamps

    public function maintenanceRecord()
    {
        return $this->belongsTo(MaintenanceRecord::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
