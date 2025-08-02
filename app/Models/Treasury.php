<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    /** @use HasFactory<\Database\Factories\TreasuryFactory> */
    use HasFactory;

    protected $fillable = [
        'account_id',
        'amount',
        'type',
        'reference',
        'note',
        'created_by',
        'updated_by',
        'transaction_date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

public function tags()
{
    return $this->morphToMany(Tag::class, 'taggable');
}


    protected static function booted()
    {
        static::creating(function ($treasury) {
            $treasury->created_by = auth()->id();
        });

        static::updating(function ($treasury) {
            $treasury->updated_by = auth()->id();
        });
    }
}
