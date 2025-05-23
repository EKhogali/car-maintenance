<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    /** @use HasFactory<\Database\Factories\PartFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'supplier_id',
    ];
    public function maintenanceRecords()
    {
        return $this->belongsToMany(MaintenanceRecord::class)
                    ->withPivot('quantity', 'unit_price')
                    ->withTimestamps();
    }

    public function usages()
    {
        return $this->hasMany(MaintenanceRecordPart::class);
    }


}
