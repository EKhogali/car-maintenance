<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    public function serviceTypes()
{
    return $this->hasMany(ServiceType::class, 'service_category_id');
}

}
