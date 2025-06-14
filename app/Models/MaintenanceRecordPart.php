<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceRecordPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_record_id',
        'part_id',
        'part_name',
        'quantity',
        'unit_price',
    ];

    public function maintenanceRecord()
    {
        return $this->belongsTo(MaintenanceRecord::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
