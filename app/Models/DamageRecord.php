<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'damage_id';

    protected $fillable = [
        'vin',
        'damage_type',
        'location',
        'description',
        'cost'
    ];

    protected $casts = [
        'cost' => 'decimal:2'
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vin');
    }
}
