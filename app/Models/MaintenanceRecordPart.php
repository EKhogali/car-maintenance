<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecordPart extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceRecordPartFactory> */
    use HasFactory;

    protected $table = 'maintenance_record_parts';

    protected $fillable = [
        'maintenance_record_id',
        'part_id',
        'quantity',
        'unit_price',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function maintenanceRecord()
    {
        return $this->belongsTo(MaintenanceRecord::class);
    }
}
