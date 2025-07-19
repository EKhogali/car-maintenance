<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    /** @use HasFactory<\Database\Factories\MechanicFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'experience_years',
        'license_number',
        'work_pct',
        'hire_date',
        'is_active',
        'notes',
    ];

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }
}
